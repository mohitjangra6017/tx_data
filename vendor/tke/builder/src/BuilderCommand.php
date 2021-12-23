<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace KineoBuilder;

use Composer\Command\BaseCommand;
use Composer\Command\UpdateCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BuilderCommand extends BaseCommand
{
    private $input;

    private $output;

    public const BUILD_TYPE_BUILD = 'build';

    public const BUILD_TYPE_COMMIT = 'build-commit';

    public const VALID_BUILD_TYPES = [
        self::BUILD_TYPE_BUILD,
        self::BUILD_TYPE_COMMIT,
    ];

    public const VALID_AUTO_BUILD_BRANCHES = '#^(integration|release|master)\/\d+$#';

    protected function configure()
    {
        $buildDescription = 'Not used when `build-type` is "' . self::BUILD_TYPE_BUILD . '".';

        $this->setName('tke-builder')
            ->addArgument(
                'build-type',
                InputArgument::OPTIONAL,
                'The build type to create. Supported build types are: ' . implode(', ', self::VALID_BUILD_TYPES),
                self::BUILD_TYPE_BUILD
            )
            ->addOption('source', 's', InputOption::VALUE_OPTIONAL, 'The source branch to start from')
            ->addOption(
                'destination',
                'b',
                InputOption::VALUE_OPTIONAL,
                'The destination branch to build to. Will prompt to delete the branch if it already exists. ' . $buildDescription
            )
            ->addOption(
                'force-destination-delete',
                null,
                InputOption::VALUE_NONE,
                'Does not prompt you to delete the destination branch (if it already exists). ' .
                'This is destructive, you cannot get that branch back again. ' . $buildDescription
            )
            ->addOption(
                'force-push',
                null,
                InputOption::VALUE_NONE,
                'Does not prompt you to push the branch, will force push it for you. ' .
                'This is destructive, as it force pushes to whatever the remote version of your destination branch is. ' . $buildDescription
            );

        $this->setHelp(<<<HELP
Performs the build process necessary to produce a working Totara: Kineo Edition code base.
Can be used to provide just the build steps for local development, or a full process for a production ready T:KE demo.
HELP
);
        $this->setDescription('Performs a full production ready build of T:KE that can be deployed to a site.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $buildType = strtolower($input->getArgument('build-type'));

        if ($this->getComposer()->getPackage()->getType() !== 'tke') {
            $output->writeln('<error>This Builder can only be run in a T:KE Composer package.</error>');
            return 1;
        }

        if (!in_array($buildType, self::VALID_BUILD_TYPES)) {
            $output->writeln(
                sprintf(
                    "<error>Build Type '%s' is not supported. Must be one of: %s</error>",
                    $buildType,
                    implode(', ', self::VALID_BUILD_TYPES)
                )
            );
            return 1;
        }

        switch ($buildType) {
            case self::BUILD_TYPE_COMMIT:
                return $this->performCommitBuild($input->getOption('source'), $input->getOption('destination'));

            case self::BUILD_TYPE_BUILD:
            default:
                return $this->performBuild();
        }
    }

    public function performBuild(): int
    {
        $this->updateDependencies();
        $this->doBuild();
        return 0;
    }

    public function performCommitBuild(string $source, string $destination): int
    {
        $this->validateGitRepo();

        $source = $this->validateSource($source);
        if ($source === null) {
            return 1;
        }

        $destination = $this->validateDestination($destination, $source);
        if ($destination === null) {
            return 1;
        }

        $this->setupBranch($source, $destination);
        $this->updateDependencies();
        $this->doBuild();
        $this->commitBranch();
        $this->pushBranch($destination);

        return 0;
    }

    /**
     * Performs the actual build itself.
     */
    public function doBuild()
    {
        $this->runCommand('npm install');
        $this->runCommand('npm run tui-build');
    }

    /**
     *
     * @param string $command
     * @param array $vars
     * @return string
     */
    private function runCommand(string $command, array $vars = []): string
    {
        if (method_exists(Process::class, 'fromShellCommandline')) {
            $process = Process::fromShellCommandline($command);
            $process->setEnv($vars);
        } else {
            $process = new Process($command, null, $vars);
        }
        $this->output->writeln("<info>Running Command: {$process->getCommandLine()}</info>");
        if (($exitCode = $process->run()) !== 0) {
            throw new ProcessFailedException($process);
        }
        return trim($process->getOutput());
    }

    /**
     * Validates the given source branch is an actual branch.
     * @param mixed $source
     * @return string|null
     */
    private function validateSource($source): ?string
    {
        if (is_null($source)) {
            // Take the currently active branch if source is not given to us.
            $source = $this->runCommand('git branch --show-current');
            if (!preg_match(self::VALID_AUTO_BUILD_BRANCHES, $source)) {
                $error = <<<MSG
<error>The source branch was automatically determined as '{$source}', however this is not supported as an automatic build branch.
If you want to build from this branch, please specify it using --source.</error>
MSG;
                $this->output->writeln($error);
                return null;
            }
        } else {
            // Check if the given branch name is actually a proper branch in the repo.
            $branch = $this->runCommand('git branch --list "$SOURCE"', ['SOURCE' => $source]);
            // `git branch --list` can output an asterisk if the branch is the current one.
            $branch = preg_replace('/^\s*\*\s*/', '', $branch);

            if ($branch !== $source) {
                $this->output->writeln(
                    "<error>The source branch '{$source}' does not exist as a valid branch in the T:KE git repo. Please check the branch exists and try again.</error>"
                );
                return null;
            }
        }

        return $source;
    }

    /**
     * Validates the given destination branch does not already exist.
     * If destination is null, uses the source branch name to create the destination branch.
     * Prompts the user to delete the branch if it does exist.
     * @param mixed $destination
     * @param string $source
     * @return string|null
     */
    private function validateDestination($destination, string $source): ?string
    {
        if (is_null($destination)) {
            // If not provided, simply prefix the source branch.
            $destination = 'build/' . $source;
            $this->output->writeln("<info>Automatically determined the source branch as {$destination}.</info>");
        }

        $repoBranch = preg_replace(
            '/^\s*\*\s*/',
            '',
            $this->runCommand('git branch --list "$DEST"', ['DEST' => $destination])
        );

        if ($repoBranch === $destination) {
            // First make sure we are not on the destination branch.
            $current = $this->runCommand('git branch --show-current');
            if ($current === $destination) {
                $this->runCommand('git checkout "$SOURCE"', ['SOURCE' => $source]);
            }

            // If destination already exists, decide how to delete it.
            $delete = false;
            $force = $this->input->getOption('force-destination-delete') ?? false;
            if ($force) {
                $this->output->writeln("<info>Forcibly removing the existing destination branch '{$destination}'.</info>");
                $delete = true;
            } else if ($this->input->isInteractive()) {
                $delete = $this->getIO()->askConfirmation("Delete the branch '{$destination}'? (y/n): ", false);
            }

            if (!$delete) {
                $this->output->writeln(
                    "<error>Not deleting the destination branch '{$destination}' and cannot continue.</error>"
                );
                return null;
            }

            $this->runCommand('git branch -D "$DEST"', ['DEST' => $destination]);
            $this->output->writeln("<info>Deleted the destination branch '{$destination}'.</info>");
        }

        return $destination;
    }

    /**
     * @param string $source
     * @param string $destination
     */
    private function setupBranch(string $source, string $destination): void
    {
        $this->runCommand('git checkout "$SOURCE"', ['SOURCE' => $source]);
        $this->runCommand('git branch "$DEST"', ['DEST' => $destination]);
        $this->runCommand('git checkout "$DEST"', ['DEST' => $destination]);
    }

    /**
     * Updates the Composer dependencies.
     */
    private function updateDependencies()
    {
        try {
            /** @var UpdateCommand $updateCommand */
            $updateCommand = $this->getApplication()->get('update');
            $updateCommand->setComposer($this->getComposer(false, true));
            $bufferedOutput = new BufferedOutput(OutputInterface::VERBOSITY_QUIET);
            $updateCommand->run(
                new ArrayInput(['--no-plugins' => true, '--no-interaction' => true, '--no-scripts' => true]),
                $bufferedOutput
            );
        } catch (\Exception $exception) {
            $this->output->writeln(
                'Failed to update Composer dependencies. The following messages from Composer may help a developer debug this issue:'
            );
            $this->output->write($bufferedOutput->fetch());
            throw $exception;
        }
    }

    private function commitBranch(): void
    {
        $this->runCommand('git add --all');
        $this->runCommand('git add --force -- vendor client/component');
        $this->runCommand('git commit --message="Automated Build" --no-verify');
    }

    /**
     * Force pushes the branch to the remote. The user must confirm the force push first.
     * @param string $destination
     * @return bool|null
     */
    private function pushBranch(string $destination): ?bool
    {
        $push = false;
        if ($this->input->getOption('force-push')) {
            $this->output->writeln("<info>Force pushing the branch '{$destination}' to the remote.</info>");
            $push = true;
        } else if ($this->input->isInteractive()) {
            $push = $this->getIO()->askConfirmation("Force push the branch '{$destination}'?", false);
        }

        if (!$push) {
            $this->output->writeln("<error>Not force pushing the branch '{$destination}' and cannot continue.</error>");
            return null;
        }

        // Just verify the name of the remote. We take the first one, as that is always the one that the repo was originally cloned from.
        // Dev note: it isn't necessarily the first one, but for 99% of use cases it is fine. If someone has modified their repo to have different remotes: 🤷️
        $remotes = $this->runCommand('git remote');
        $remote = strtok($remotes, PHP_EOL);
        $this->runCommand('git push --force "$REMOTE" "$DEST"', ['REMOTE' => $remote, 'DEST' => $destination]);

        return true;
    }

    /**
     * Ensure that the repo is clean, and prompt the user to clean the repo if not.
     * Note that this will infinite loop if the user does not properly clean the repo.
     */
    private function validateGitRepo()
    {
        do {
            $status = $this->runCommand('git status --porcelain');
            $items = explode(PHP_EOL, $status);
            // We only allow a modified composer.json file. Anything else, ask what to do.
            $changedItems = array_filter(
                $items,
                function ($item) {
                    return preg_match('/^\s?(M\s?composer\.json|\?{1,2}\s?.*?)\s?$/', $item) === false;
                }
            );

            if (count($changedItems) === 0) {
                // NOTE: this return is the only way out of this infinite loop. The user MUST stash or clean the repo, else be stuck here forever.
                return;
            }

            if (!$this->input->isInteractive()) {
                throw new \InvalidArgumentException('The Git repo contains modified files. Fix these files before attempting a build, as these files may be overwritten.');
            }

            // git status --porcelain always outputs 2 characters, followed by 1 space. Remove these to get the affected files only.
            $changedItems = array_map(
                function ($item) {
                    return substr($item, 3);
                },
                $changedItems
            );

            $question = 'There are modified items in your git repo. To continue, these files must be (s)tashed or (c)leaned, otherwise (l)ist the changes or show the (h)elp: [c, h, l, s, ?] ';
            $action = $this->getIO()->ask($question);

            switch (strtolower(trim($action))) {
                case 's':
                    $this->runCommand('git stash --all --include-untracked');
                    break;

                case 'c':
                    // Do a heavy handed clean up. First reset everything except the composer.json.
                    $this->runCommand('git reset --hard HEAD -- !composer.json');
                    // Then force clean anything that is untracked.
                    $this->runCommand('git clean --force -d');
                    break;

                case 'l':
                    $this->output->writeln('Files that will be stashed or cleaned:');
                    $this->output->writeln($changedItems);
                    break;

                case 'h':
                case '?':
                default:
                    $this->output->writeln(
                        [
                            'The available actions are:',
                            '  c      - Clean the files that have been modified. This will reset any files flagged by git as having changed back to their previous state. <error>THIS IS DESTRUCTIVE</error>',
                            '  h, ?   - Display this help text',
                            '  l      - List the output of the git status to see which files will be affected',
                            '  s      - Stash the changes. Will attempt to stash all changes made so you can restore them later using git apply.',
                        ]
                    );
                    break;
            }
        } while (true);
    }
}