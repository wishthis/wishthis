<?php

namespace wishthis;

class PageControllerUpdate extends PageController
{
    protected string $id = 'update';

    public function __construct(array $parameters = [])
    {
        $this->pageTitle = __('Update');

        parent::__construct();
    }

    public function default(): void
    {
        global $options;

        if (\version_compare($options->version, '1.0.0', '<=')) {
            $messageContent = __('Attention! You need to update your config.php in order for wishthis to continue working. Please refer to your config-sample.php. In particular the namespace must be added!');
            $messageHeader  = __('Warning');
            $messageType    = MessageType::WARNING;
            $message        = new Message(
                $messageContent,
                $messageHeader,
                $messageType
            );

            $_SESSION['messages'][] = $message;
        }

        $this->placeholders['MIGRATION_HEADING']     = __('Database migration');
        $this->placeholders['MIGRATION_DESCRIPTION'] = __('Thank you for updating wishthis! To complete this update, some changes are required to the database structure.');
        $this->placeholders['MIGRATION_SUBMIT']      = \sprintf(
            /** TRANSLATORS: %1$s: The new wishthis version */
            __('Migrate to v%1$s'),
            VERSION
        );

        parent::render();
    }

    public function update(): void
    {
        global $options;

        $user        = User::getCurrent();
        $userPower   = $user->getPower();
        $userIsAdmin = 100 === $userPower;

        if (!$userIsAdmin) {
            die('You must have at least 100 power to update.');
        }

        $versionsDirectory = ROOT . '/src/update';
        $versionsContents  = \scandir($versionsDirectory);
        $versions          = [];

        foreach ($versionsContents as $filename) {
            $filepath = $versionsDirectory . '/' . $filename;
            $pathinfo = \pathinfo($filepath);

            if ('sql' === $pathinfo['extension']) {
                $versions[] = [
                    'version'  => \str_replace('-', '.', $pathinfo['filename']),
                    'filepath' => $filepath,
                ];
            }
        }

        foreach ($versions as $version) {
            if (\version_compare($options->version, $version['version'], '<')) {
                $sql = \file_get_contents($version['filepath']);

                if ($sql) {
                    $database->query($sql);
                }
            }
        }

        /** Update version */
        $options->setOption('version', VERSION);
        $options->setOption('updateAvailable', false);

        /** Update service-worker.js */
        require ROOT . '/src/assets/js/service-worker.js.php';

        $messageContent = \sprintf(
            /** TRANSLATORS: %1$s: The new wishthis version */
            __('Database successfully migrated to v%1$s.'),
            VERSION
        );
        $messageHeader = __('Update complete');
        $messageType   = MessageType::SUCCESS;
        $message       = new Message(
            $messageContent,
            $messageHeader,
            $messageType
        );

        $_SESSION['messages'][] = $message;

        \redirect('/');
    }
}
