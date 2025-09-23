<?php
class FooterDashboard
{
    private $footer_dashboard;

    public function __construct()
    {

        $this->footer_dashboard = '
            <script src="../../src/node_modules/sweetalert/dist/sweetalert.min.js"></script>
            <script src="../../src/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
            <script src="../../src/node_modules/jquery/dist/jquery.min.js"></script>
            <script src="../../src/js/loader.js"></script>
            <script src="../../src/js/form.js"></script>
            <script src="../../src/js/tooltip.js"></script>
            <script src="../../src/js/admin.js"></script>
            <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
            <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
            <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
            <script src="https://cdn.amcharts.com/lib/5/themes/Responsive.js"></script>
            ';
    }

    public function getFooterDashboard()
    {
        return $this->footer_dashboard;
    }
}

class FooterSignin
{
    private $footer_signin;

    public function __construct()
    {

        $this->footer_signin = '
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
            <script src="src/node_modules/sweetalert/dist/sweetalert.min.js"></script>
            <script src="src/node_modules/jquery/dist/jquery.min.js"></script>
            <script src="src/js/signin.js"></script>
            ';
    }

    public function getFooterSignin()
    {
        return $this->footer_signin;
    }
}
