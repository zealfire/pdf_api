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
use \TCPDF;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A PDF generator plugin for the mPDF library.
 *
 * @PdfGenerator(
 *   id = "tcpdf",
 *   module = "pdf_api",
 *   title = @Translation("TCPDF"),
 *   description = @Translation("PDF generator using the TCPDF generator.")
 * )
 */
class tcpdfGenerator extends PdfGeneratorBase implements ContainerFactoryPluginInterface {

  /**
   * The global options for TCPDF.
   *
   * @var array
   */
  protected $options = array();

  /**
   * Instance of the TCPDF class library.
   *
   * @var \TCPDF
   */
  protected $generator;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition, TCPDF $generator) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->generator = $generator;
    //$this->setOptions(array('binary' => 'C://"Program Files"/wkhtmltopdf/bin/wkhtmltopdf.exe'));
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('tcpdf')
    );
  }
  
  /**
   * {@inheritdoc}
   */
  public function setHeader() {
    $this->generator->SetPrintHeader();
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
    if($orientation == 'portrait')
      $orientation = 'P';
    else
      $orientation = 'L';
    $this->generator->setPageOrientation($orientation);
  }

  /**
   * {@inheritdoc}
   */
  public function setPageSize($page_size) {
    if ($this->isValidPageSize($page_size)) {
      $this->generator->AddPage("", $page_size, false, true);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setFooter() {
    $this->generator->writeHtmlCell($widhheader,3,20,4,'<p>Page '.$this->generator->getAliasNumPage().' of  '.' '.$this->generator->getAliasNbPages().'</p>','',1,0,false,'R');
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
    $this->generator->Output('htmlout.pdf', 'I');
  }

  /**
   * {@inheritdoc}
   */
  public function stream($html, $filelocation) {
    $this->generator->Output($filelocation, 'D');
  }

}
