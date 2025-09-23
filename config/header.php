<?php
include_once 'settings-configuration.php';
$config = new SystemConfig();

class HeaderDashboard
{
    private $header_dashboard;
    private $config;

    public function __construct($config)
    {
        $this->config = $config; // Set the config property
        // Generate the header HTML code
        $this->header_dashboard = '
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="shortcut icon" href="../../src/img/' . $this->config->getSystemFavicon() . '">
            <link rel="stylesheet" href="../../src/node_modules/bootstrap/dist/css/bootstrap.min.css">
            <link rel="stylesheet" href="../../src/node_modules/boxicons/css/boxicons.min.css">
            <link rel="stylesheet" href="../../src/node_modules/aos/dist/aos.css">
            <link rel="stylesheet" href="../../src/css/admin.css?v=<?php echo time(); ?>">
        ';
    }

    public function getHeaderDashboard()
    {
        return $this->header_dashboard;
    }
}

class HeaderSignin
{
    private $header_signin;
    private $config;

    public function __construct($config)
    {
        $this->config = $config; // Set the config property
        // Generate the header HTML code
        $this->header_signin = '
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="shortcut icon" href="src/img/' . $this->config->getSystemFavicon() . '">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            <script src="https://www.google.com/recaptcha/api.js?render=' . $this->config->getSKey() . '"></script>
            <link rel="stylesheet" href="src/css/signin.css?v=' . time() . '">
        ';
    }

    public function getHeaderSignin()
    {
        return $this->header_signin;
    }
}
?>
