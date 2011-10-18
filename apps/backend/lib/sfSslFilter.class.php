<?php
/**
 * @author Graham Christensen
 *          graham@grahamc.com
 */
class sslFilter extends sfFilter {
    public function execute ($filterChain) {
        $context = $this->getContext();
        $request = $context->getRequest();

        // Perform strict checking of security
        // IE: If it's HTTPS and shouldn't be, make it HTTP
        if (sfConfig::has('app_ssl_strict')) {
            $only_explicit = (bool)
                             sfConfig::get('app_ssl_strict');
        } else {
            $only_explicit = false;
        }

        // Get a list of all the modules to check for
        $modules = sfConfig::get('app_ssl_modules');

        // Set the modules variable to an array, this is
        // if it's not configured for this particular environment.
        if (!is_array($modules)) {
            $modules = array();
        }

        // Store the module name and action name into variables
        // to simplify the code, and reduce function calls.
        $module_name = $context->getModuleName();
        $action_name = $context->getActionName();

        // Check if the current request matches a security module
        // If the module or module & action is specified, then
        // ensure it's correctly set.
        $listed = false;
        foreach ($modules as $action) {
            // If the module name is listed
            if ($action['module'] == $module_name) {
                // If the whole module is listed, or the action
                // specifically
                if (!isset($action['action'])
                    || $action_name == $action['action']) {
                    $listed = true;
                    break;
                }
            }
        }

        $is_secure = $request->isSecure();

        // If modules have to be explicitly listed, it is
        // secure, and it's not listed - then redirect
        if ($only_explicit && $is_secure && !$listed) {
            return self::doRedirect($context);
        }

        // If it's not secure, and it's listed as having to be
        if (!$is_secure && $listed) {
            return self::doRedirect($context);
        }

        // Continue on with the chain, but it will only do that if
        // we didn't need to redirect.
        $filterChain->execute();
    }

    public static function doRedirect($context) {
        $request = $context->getRequest();
        $controller = $context->getController();

        // Determine which direction we want to go
        if ($request->isSecure()) {
            // Switch to insecure
            $from = 'https://';
            $to   = 'http://';
        } else {
            // Switch to secure
            $from = 'http://';
            $to   = 'https://';
        }

        $redirect_to = str_replace($from, $to, $request->getUri());
        return $controller->redirect($redirect_to, 0, 301);
    }
}
?>