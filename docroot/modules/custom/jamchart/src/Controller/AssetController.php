<?php

namespace Drupal\jamchart\Controller;

use Drupal\dynamic_asset\Controller\DynamicAssetBaseController;

class AssetController extends DynamicAssetBaseController {

  protected function getJS() {
    return '
            google.charts.load("current", {packages:["calendar"]});
                google.charts.setOnLoadCallback(drawChart);
        
                function drawChart() {
                    var dataTable = new google.visualization.DataTable();
                    dataTable.addColumn({ type: \'date\', id: \'Date\' });
                    dataTable.addColumn({ type: \'number\', id: \'Won/Loss\' });
                    dataTable.addRows([
                        [ new Date(2012, 3, 12), 1 ],
                        [ new Date(2012, 4, 11), 1 ],
                        [ new Date(2012, 5, 9), 1 ],
                        [ new Date(2012, 6, 8), 1 ],
                        [ new Date(2012, 7, 22), 1 ]
                        // Many rows omitted for brevity.
        
                    ]);
        
                    var chart = new google.visualization.Calendar(document.getElementById(\'calendar_basic\'));
        
                    var options = {
                        title: "Jam frequency",
                        height: 350,
                    };
        
                    chart.draw(dataTable, options);
                }
            ';
  }

  /**
   * Render scripts.
   */
  public function script() {
    return $this->js($this->getJS());
  }

}