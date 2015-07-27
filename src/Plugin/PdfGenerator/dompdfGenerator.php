<?php

/**
 * @file
 * Contains \Drupal\pdf_api\Plugin\mpdfGenerator.
 */

namespace Drupal\pdf_api\Plugin\PdfGenerator;

use Drupal\pdf_api\Plugin\PdfGeneratorBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\pdf_api\Annotation\PdfGenerator;
use Drupal\Core\Annotation\Translation;
use \DOMPDF;
use Drupal\pdf_api\Plugin\PdfGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

// disable DOMPDF's internal autoloader if you are using Composer
define('DOMPDF_ENABLE_AUTOLOAD', false);
//include the DOMPDF config file (required)
require __DIR__."../../../../vendor/dompdf/dompdf/dompdf_config.inc.php";

/**
 * A PDF generator plugin for the dompdf library.
 *
 * @PdfGenerator(
 *   id = "dompdf",
 *   module = "pdf_api",
 *   title = @Translation("DOMPDF"),
 *   description = @Translation("PDF generator using the DOMPDF generator.")
 * )
 */
class dompdfGenerator extends PdfGeneratorBase implements ContainerFactoryPluginInterface {

  /**
   * The global options for TCPDF.
   *
   * @var array
   */
  protected $options = array();

  /**
   * Instance of the DOMPDF class library.
   *
   * @var \DOMPDF
   */
  protected $generator;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition, DOMPDF $generator) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->generator = $generator;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('dompdf')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setter($pdf_content, $pdf_location, $save_pdf, $paper_orientation, $paper_size, $footer_content, $header_content) {
    $this->setPageOrientation($paper_orientation);
    $this->addPage($pdf_content);
    $this->setFooter("");
      if($save_pdf) {
        $filename = $pdf_location;
        if(empty($filename)) {
          $filename = str_replace("/", "_", \Drupal::service('path.current')->getPath());
          // @todo Be consistent in the use of single or double quotes. Know
          //   that single quotes perform slightly better. My advise is to use
          //   single quotes when possible, and use double when required.
          $filename = substr($filename, 1);
          // @todo What is removed here? CurrentPathStack::getPath docs say: "Returns the path, without leading slashes."
        }
        $this->stream("", $filename . '.pdf');
      }
      else
        $this->send("");
    $this->generator->load_html($pdf_content);
    $this->generator->render();
    $this->generator->set_paper("", $paper_orientation);
    $this->generator->stream("sample.pdf",array('Attachment'=>0));
  }

  /**
   * {@inheritdoc}
   */
  public function getObject() {
    return $this->generator;
  }

  /**
   * {@inheritdoc}
   */
  public function setHeader($text) {
    // @todo still to be found out.
  }

  /**
   * {@inheritdoc}
   */
  public function addPage($html) {
    $this->generator->AddPage();
    $this->generator->writeHTML($html);
  }

  /**
   * {@inheritdoc}
   */
  public function setPageOrientation($orientation = PdfGeneratorInterface::PORTRAIT) {
    if($orientation == PdfGeneratorInterface::PORTRAIT)
      $orientation = 'P';
    else
      $orientation = 'L';
    $this->generator->set_paper("", $orientation);
  }

  /**
   * {@inheritdoc}
   */
  public function setPageSize($page_size) {
    if ($this->isValidPageSize($page_size)) {
    $this->generator->set_paper($page_size);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setFooter($text) {
    // @todo still to be found out.
  }

  /**
   * {@inheritdoc}
   */
  public function save($location) {
    $this->preGenerate();
    $this->generator->send($location);
  }

  /**
   * {@inheritdoc}
   */
  public function send($html) {
    // @todo Incomplete, $html is not being used.
    $this->generator->Output('htmlout.pdf', 'I');
  }

  /**
   * {@inheritdoc}
   */
  public function stream($html, $filelocation) {
    // @todo Incomplete, $html is not being used.
    $this->generator->Output($filelocation, 'D');
  }

}
