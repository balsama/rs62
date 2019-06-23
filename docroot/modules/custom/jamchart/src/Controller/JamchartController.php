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
      "#markup" => "<div class='col 12'><div id='calendar_basic'><p>Please wait... rendering the Jam Chart</p></div><p class='col m8'>Days that a session was recorded are highlighted in orange with varying degrees of intensity based on the number of musicians at the jam.</p></div>"
    ];
    return $element;
  }

}