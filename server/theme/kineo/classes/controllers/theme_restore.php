<?php
/**
 * This file is part of Totara Learn
 *
 * Copyright (C) 2020 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Johannes Cilliers <johannes.cilliers@totaralearning.com>
 * @package totara_tui
 */

namespace theme_kineo\controllers;

use coding_exception;
use context;
use context_system;
use context_user;
use core\files\file_helper;
use core\notification;
use dml_exception;
use file_exception;
use moodle_exception;
use moodle_url;
use require_login_exception;
use stored_file_creation_exception;
use theme_kineo\Service\ThemeBackupService;
use totara_mvc\admin_controller;
use totara_mvc\tui_view;
use totara_tenant\entity\tenant;

class theme_restore extends admin_controller
{
    /**
     * @var string
     */
    protected $theme;

    /**
     * @inheritDoc
     */
    protected $admin_external_page_name;

    /**
     * @inheritDoc
     */
    protected $layout = 'standard';

    /** @var int */
    private $tenantId;

    /**
     * @inheritDoc
     */
    protected function setup_context(): context
    {
        return context_system::instance();
    }

    /**
     * @inheritDoc
     */
    public function process(string $action = '')
    {
        $this->theme = $this->get_required_param('theme_name', PARAM_COMPONENT);
        $this->tenantId = $this->get_required_param('tenant_id', PARAM_INT);
        $this->admin_external_page_name = "{$this->theme}_editor";
        parent::process($action);
    }

    /**
     * @return tui_view
     * @throws dml_exception
     * @throws require_login_exception
     * @throws coding_exception
     * @throws file_exception
     * @throws moodle_exception
     * @throws stored_file_creation_exception
     */
    public function action(): tui_view
    {
        global $USER;

        require_login(null, false);
        $this->require_capability('moodle/site:config', context_system::instance());

        if (!empty($this->tenantId)) {
            $tenant = tenant::repository()->find($this->tenantId);
            if (empty($tenant)) {
                throw new moodle_exception('errorinvalidtenant', 'totara_tenant');
            }
        }

        $url =
            new moodle_url(
                '/theme/kineo/classes/controllers/theme_restore.php',
                ['theme_name' => $this->theme, 'tenant_id' => $this->tenantId]
            );

        $this->get_page()->set_url($url);
        $this->set_url($url);

        $this->processRestoreFileSubmission();

        // Create a file ready to receive our restore file. This won't be saved anywhere other than draft files.
        $fileHelper = new file_helper(
            'theme_kineo',
            'restore_file',
            context_system::instance()
        );
        $file = $fileHelper->create_file_area($USER->id);

        $props = [
            'tenantId' => $this->tenantId,
            'repositoryId' => $file->get_repository_id(),
            'fileUrl' => $file->get_url(),
            'itemId' => $file->get_draft_id(),
            'contextId' => context_system::instance()->id,
            'maxFileSize' => display_size(get_max_upload_file_size())
        ];

        $tui_view = tui_view::create('theme_kineo/pages/ThemeRestore', $props);
        $tui_view->set_title(get_string('theme_restore:title', 'theme_kineo'));

        return $tui_view;
    }

    /**
     * @throws coding_exception
     * @throws file_exception
     * @throws moodle_exception
     * @throws stored_file_creation_exception
     */
    private function processRestoreFileSubmission(): void
    {
        global $USER;

        $draftId = optional_param('draft_id', 0, PARAM_INT);
        if (empty($draftId)) {
            return;
        }

        // Get files in user draft area.
        $fileHelper = new file_helper(
            'user',
            'draft',
            context_user::instance($USER->id)
        );
        $fileHelper->set_item_id($draftId);
        $files = $fileHelper->get_stored_files();
        if (count($files) != 1) {
            notification::error('No file found');
            return;
        }
        $file = reset($files);
        $content = $file->get_content();
        $json = json_decode($content, true);
        if (json_last_error() !== 0) {
            notification::error('Failed to JSON decode file content');
            return;
        }

        $service = new ThemeBackupService($this->tenantId);
        if ($service->restoreBackup($json)) {
            notification::success('Theme Successfully Restored');
        } else {
            notification::error('Failed to restore backup');
        }

        $file->delete();

        redirect(
            new moodle_url('/theme/kineo/theme_restore.php', ['theme_name' => 'kineo', 'tenant_id' => $this->tenantId])
        );
    }
}