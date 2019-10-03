<?php
/**
 * HAWK module for Craft CMS 3.x
 *
 * Adds HAWK adjustments for CraftCMSN
 *
 * @link      http://hawk.ca
 * @copyright Copyright (c) 2019 HAWK
 */

namespace modules\hawk\widgets;

use modules\hawk\HAWKModule;
use modules\hawk\assetbundles\emailsupportwidget\EmailSupportWidgetAsset;

use Craft;
use craft\base\Widget;

/**
 * HAWK Widget
 *
 * Dashboard widgets allow you to display information in the Admin CP Dashboard.
 * Adding new types of widgets to the dashboard couldn't be easier in Craft
 *
 * https://craftcms.com/docs/plugins/widgets
 *
 * @author    HAWK
 * @package   HAWKModule
 * @since     1.0.0
 */
class EmailSupport extends Widget
{

    // Public Properties
    // =========================================================================

    // Static Methods
    // =========================================================================

    /**
     * Returns whether the widget can be selected more than once.
     *
     * @return bool
     */
    protected static function allowMultipleInstances() : bool
    {
        return false;
    }

    // Static Methods
    // =========================================================================

    /**
     * Returns the display name of this class.
     *
     * @return string The display name of this class.
     */
    public static function displayName(): string
    {
        return Craft::t('hawk', 'Email Support');
    }

    /**
     * Returns the path to the widget’s SVG icon.
     *
     * @return string|null The path to the widget’s SVG icon
     */
    public static function iconPath()
    {
        return Craft::getAlias("@hawk/assetbundles/emailsupportwidget/dist/img/EmailSupport-icon.svg");
    }

    /**
     * Returns the widget’s maximum colspan.
     *
     * @return int|null The widget’s maximum colspan, if it has one
     */
    public static function maxColspan()
    {
        return null;
    }

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        return $rules;
    }

    /**
     * Returns the widget's body HTML.
     *
     * @return string|false The widget’s body HTML, or `false` if the widget
     *                      should not be visible. (If you don’t want the widget
     *                      to be selectable in the first place, use {@link isSelectable()}.)
     */
    public function getBodyHtml()
    {
        Craft::$app->getView()->registerAssetBundle(EmailSupportWidgetAsset::class);

        return Craft::$app->getView()->renderTemplate(
            'hawk/_components/widgets/EmailSupport_body'
        );
    }
}
