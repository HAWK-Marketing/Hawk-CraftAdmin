<?php
/**
 * HAWK module for Craft CMS 3.x
 *
 * Adds HAWK adjustments for CraftCMSN
 *
 * @link      http://hawk.ca
 * @copyright Copyright (c) 2019 HAWK
 */

namespace modules\hawk\controllers;

use modules\hawk\HAWKModule;

use Craft;
use craft\web\Controller;
use craft\helpers\Template;
use craft\mail\Message;
use yii\helpers\Markdown;

/**
 * EmailSupport Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your module’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    HAWK
 * @package   HAWKModule
 * @since     1.0.0
 */
class EmailSupportController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['index'];

    // Public Methods
    // =========================================================================

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $result = 'Not Found.';

        return $result;
    }

    /**
     * @return mixed
     */
    public function actionSendEmail()
    {
        $params = Craft::$app->getRequest()->getBodyParams();

        $results = [
            'status' => 'error',
            'message' => 'DATA_NOT_VALID',
        ];

        // set defaults
        //$contactEmail = 'thompson.steve@hawk.ca';
        $contactEmail = 'verbeek.steven@hawk.ca';
        $sendDetails = true;
        $siteName = Craft::$app->getSites()->getCurrentSite()->name ?? 'Craft CMS Website';
        $timestamp = date("F j, Y, g:i a");
        $userEmail = Craft::$app->getUser()->getIdentity()->email;
        $userFriendlyName = Craft::$app->getUser()->getIdentity()->friendlyName;

        // prepare mail message
        $emailMessage = "<h2>Email Support Message from " . $siteName . "</h2>"
            ."<p><b>Date:</b> " . $timestamp . "<br>"
            ."<b>User:</b> " . $userFriendlyName . " (<a href='mailto:" . $userEmail . "'>" . $userEmail . "</a>)</p>";

        if ($params['message'] ?? false) {
            $emailMessage .= '<hr><h3>Message</h3>' . Template::raw(Markdown::process($params['message'])) . '<br>';
        }

        if ($sendDetails) {

            // start table
            $emailMessage .= "<hr><h3>Support Details</h3><table width='100%'><tbody>";

            // system info
            $emailMessage .= "<tr><td width='30%' style='border-bottom: 1px solid lightgrey'><br><b>SYSTEM INFO</b></td><td style='border-bottom: 1px solid lightgrey'>&nbsp;</td></tr>"
                ."<tr><td><b>Edition</b></td><td>" . Craft::$app->getEditionName() . "</td></tr>"
                ."<tr><td><b>Craft Environment</b></td><td>" . CRAFT_ENVIRONMENT . "</td></tr>"
                ."<tr><td><b>System On</b></td><td>" . (Craft::$app->getIsLive() ? 'YES' : 'NO') . "</td></tr>"
                ."<tr><td><b>Dev Mode</b></td><td>" . (Craft::$app->getConfig()->getGeneral()->devMode ? 'YES' : 'NO') . "</td></tr>";

            $emailMessage .= "<tr><td width='30%' style='border-bottom: 1px solid lightgrey'><br><br><b>REQUEST INFO</b></td><td style='border-bottom: 1px solid lightgrey'>&nbsp;</td></tr>"
                ."<tr><td><b>OS</b></td><td>" . Craft::$app->getRequest()->getClientOs() . "</td></tr>"
                ."<tr><td><b>User Agent</b></td><td>" . Craft::$app->getRequest()->getUserAgent() . "</td></tr>"
                ."<tr><td><b>User IP</b></td><td>" . Craft::$app->getRequest()->getUserIP() . "</td></tr>";

            $emailMessage .= "<tr><td width='30%' style='border-bottom: 1px solid lightgrey'><br><br><b>BROWSER INFO</b></td><td style='border-bottom: 1px solid lightgrey'>&nbsp;</td></tr>";

            foreach ($params['browserInfo'] as $key => $item) {
                $emailMessage .= "<tr><td><b>" . $key . "</b></td><td>" . $item . "</td></tr>";
            }

            $plugins = Craft::$app->getPlugins()->getAllPlugins();

            if ($plugins ?? false) {
                $emailMessage .= "<tr><td width='30%' style='border-bottom: 1px solid lightgrey'><br><br><b>PLUGINS</b></td><td style='border-bottom: 1px solid lightgrey'>&nbsp;</td></tr>";

                foreach ($plugins as $item) {
                    $emailMessage .= "<tr><td>&nbsp;</td><td>" . $item->name . "</td></tr>";
                }
            }

            // close table
            $emailMessage .= "</tbody></table>";
        }

        $emailSubject = "Email Support Message from " . $siteName;

        // setup mail message
        $message = new Message();

        $message->setFrom([$userEmail => $userFriendlyName]);
        $message->setTo($contactEmail);
        $message->setSubject($emailSubject);
        $message->setHtmlBody($emailMessage);

        $emailSent = Craft::$app->mailer->send($message);

        if ($emailSent) {
            $results = [
                'status' => 'success',
                'message' => 'EMAIL_SENT',
            ];
        }

        // save new admin log
        return $this->asJson($results);
    }
}
