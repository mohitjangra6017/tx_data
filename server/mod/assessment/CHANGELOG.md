# Changelog

## [unreleased]
### Added
- Archiving of completed attempts, e.g. on recertification.

## [unreleased]
### Added
- Direct assignment of evaluator/reviewer roles to learners.
- Date/time question type.
- Due dates at the activity level.
- Meta/container question type.
- Empty responses to questions without input boxes now return "No response" instead of showing blank.
- Stage locking based on previous stage completions.

## Fixed
- Progress can be saved even if required fields are empty.
- Stage question sort order recalculates correctly after removing a question.

## [12.0.1]
### Changed
- Assessment detail report combines all role answers into a single row.  Each question is prefixed with the role name answering the question.

## [12.0.0]
### Added
- Backup and restore for all non-user data.
- Long-form answer question type.
- Submission alert if missing required fields.

### Fixed
- Role rule sets no longer allows role deletion once version is activated.
- Fix rule change submissions while block editing mode activated.
- Non-question types no longer throw fatal exception when failing form validation.
- Question fields validate to 255 character limit instead of throwing a database write error.
- Roles without actions will be presented with read-only actions instead of submission actions.

## [2.1.2] - 2020-02-28

### Fixed
- Multi-choice answers now require at least one answer in configuration.
- Question permissions no longer fail to update if previously set to 'Only view after submission'
- Single evaluator/reviewers can no longer switch roles if not assigned while satisfying rules.

## [2.1.1] - 2019-11-26

### Added
- Error message for users missing capability to view dashboards.
- PHPUnit tests for manager rule sql.

### Changed
- Stage status now shows other roles' status if no role action is required instead of 'Complete'.
- Status shown on questions page now reflects stage status rather than attempt status.

### Fixed
- Evaluators and reviewers are unassigned when made ineligible, even when they were the last eligible evaluator.
- Evaluators and reviewers can view all dashboard tabs.
- Fix SQL injection warning with manager rules.
- Indirect manager rules no longer return direct managers.
- Record visibility for dashboard fixed for attempts with multiple evaluators/reviewers.
- Reviewer roles see return to dashboard from stage select page instead of to the course.
- Evaluators/reviewers missing course access may now configure missing roles when first accessing asssessment.

## [2.1.0] - 2019-11-12

### Added
- Separate reviewers may be added to an assessment.
- This handy changelog! .htaccess file also added to hide the changelog to address security concerns.

### Changed
- Dashboard redesigned to a single page with multiple tabs for filtering available assessments.

### Fixed
- Added missing language strings for some errors.
