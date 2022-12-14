<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony\SupplementaryAdmin;

use Controller;
use Spark\EParagony\Constants;

class FormFieldGenerator
{
    public static function password($name, $value = '')
    {
        $value = htmlspecialchars($value);
        $mainString = '
            <div class="eparagony-form-password-pack">
                <input type="hidden" name="%s" value="%s">
                <input type="password" value="%s">
                <span class="js-eye" style="display: none;">👁️</span>
            </div>
        ';
        $substitutes = [
            htmlspecialchars($name),
            $value,
            $value,
        ];

        return vsprintf($mainString, $substitutes);
    }

    public static function addJS(Controller $controller, $path)
    {
        $controller->addJS($path . 'views/js/config.js?v=' . Constants::ADMIN_SCRIPT_VERSION);
    }
}
