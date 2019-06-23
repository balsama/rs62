<?php

namespace Drupal\jamchart\Controller;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\dynamic_asset\Controller\DynamicAssetBaseController;
use Drupal\jamchart\Request;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AssetController extends DynamicAssetBaseController implements ContainerInjectionInterface {

  /**
   * @var \GuzzleHttp\Client
   */
  protected $client;

  /**
   * @var Request
   */
  protected $request;

  public function __construct(ClientInterface $client) {
    $this->client = $client;
    $this->request = new Request(\Drupal::request()->getSchemeAndHttpHost() . base_path(), $this->client);
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('http_client')
    );
  }

  protected function getJS() {
    $sessions_info = $this->getSessionInfo();
    $sessions_processed = [];
    foreach ($sessions_info as $session_info) {
      $sessions_processed[] = $this->process_session_info($session_info);
    }
    return $this->injectDates($sessions_processed);
  }

  protected function process_session_info(\stdClass $session_info) {
    $date_parts = explode('-', $session_info->attributes->field_session_date);
    $musician_count = count($session_info->relationships->field_musicians->data);
    $month = $date_parts[1] - 1;
    return "[ new Date($date_parts[0], $month, $date_parts[2]), $musician_count ]";
  }

  protected function getSessionInfo() {
    $request = $this->request->request('jsonapi/node/session?fields[node--session]=field_session_date,field_musicians');
    return $request->data;
  }

  /**
   * Render scripts.
   */
  public function script() {
    return $this->js($this->getJS());
  }

  protected function injectDates($sessions_processed) {
    return '
            google.charts.load("current", {packages:["calendar"]});
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    var dataTable = new google.visualization.DataTable();
                    dataTable.addColumn({ type: "date", id: "Date" });
                    dataTable.addColumn({ type: "number", id: "Number of musicians" });
                    dataTable.addRows(['
                      . implode(',', $sessions_processed) .
                    ']);

                    var chart = new google.visualization.Calendar(document.getElementById("calendar_basic"));

                    var options = {
                        title: "Session dates with number of musicians",
                        height: 500,
                        calendar: {
                          monthOutlineColor: {
                            stroke: "gray",
                            strokeOpacity: 0.8,
                            strokeWidth: 1
                          },
                          focusedCellColor: {
                            stroke: "#9e9e9e",
                            strokeOpacity: 1,
                            strokeWidth: 2
                          }
                        },
                        colorAxis: {colors:["#ffccbc", "#ff7043"]},
                        noDataPattern: {
                          backgroundColor: "#f5f5f5",
                          color: "#f5f5f5"
                        }
                    };

                    chart.draw(dataTable, options);
                }
            ';
  }
}