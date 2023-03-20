<?php

/**
 * API
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

class API
{
    /**
     * Non-Static
     */
    private string $module;
    private string $module_path;
    private array $input;

    public function __construct()
    {
        global $options;

        $this->input = $this->getRequestVariables();

        $this->module      = $this->input['module'] ?? '';
        $this->module_path = ROOT . '/src/api/' . $this->module . '.php';
    }

    public function do()
    {
        if (file_exists($this->module_path)) {
            ob_start();

            $response = array(
                'success' => false,
            );

            require $this->module_path;

            $response['warning'] = ob_get_clean();
            $response['success'] = true;

            header('Content-type: application/json; charset=utf-8');
            echo json_encode($response);
        } else {
            http_response_code(404);
            ?>
            <h1>Not found</h1>
            <p>The API module "<?= $this->module ?>" was not found.</p>
            <?php
        }

        die();
    }

    private function getRequestVariables(): array
    {
        $request_variables = $_GET;

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $request_variables = array_merge($request_variables, $_POST);
                break;

            default:
                parse_str(file_get_contents('php://input'), $_INPUT);

                $request_variables = array_merge($request_variables, $_INPUT);
                break;
        }

        return $request_variables;
    }

    private function response(int $http_code): void
    {
        http_response_code($http_code);
        die();
    }
}
