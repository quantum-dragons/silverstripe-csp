<?php
namespace NSWDPC\Utilities\ContentSecurityPolicy;
use Silverstripe\Core\Extension;
use SilverStripe\Versioned\Versioned;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Config\Config;

/**
 * Provides an extension method so that the SiteTree can gather the CSP meta tag if that is set
 * @author james.ellis@dpc.nsw.gov.au
 */
class SiteTreeExtension extends Extension {

  /**
   * Check to see if a meta tag can be returned
   */
  private function checkCanRun() {
    $whitelisted_controllers = Config::inst()->get( Policy::class, 'whitelisted_controllers');
    $controller = Controller::curr();
    if( is_array($whitelisted_controllers) && in_array(get_class($controller), $whitelisted_controllers) ) {
      //SS_Log::log( "Not running in whitelisted controller:" . get_class($this->owner), SS_Log::DEBUG);
      return false;
    }
    return true;
  }

  public function MetaTags(&$tags) {
    if(!$this->checkCanRun()) {
      return;
    }

    // get the default policy
    $policy = Policy::get()->filter( ['Enabled' => 1, 'DeliveryMethod' => 'MetaTag'] )->first();
    $stage = Versioned::get_stage();
    if($stage == Versioned::LIVE) {
      // live
      $policy = $policy->filter('IsLive', 1);
    }
    $policy = $policy->first();
    if(empty($policy->ID)) {
      return;
    }

    $data = $policy->HeaderValues();

    // Note that reporting is ignored when using a meta tag
    $tags .= "<meta http-equiv=\"{$data['header']}\" content=\"" . $data['policy_string'] . "\">\n";

  }
}
