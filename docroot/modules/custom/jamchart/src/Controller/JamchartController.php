<?php

namespace Drupal\jamchart\Controller;

use Drupal\Core\Controller\ControllerBase;

class JamchartController extends ControllerBase {

  /**
   * Jamchart page holder.
   *
   * @return array
   *   A render array. Protect at all costs.
   */
  public function jamChart() {
    $element = [
      "#attached" => [
        'library' => 'jamchart/jamchart'
      ],
      "#markup" => "<div id='calendar_basic'>chart here</div>"
    ];
    return $element;
  }

}