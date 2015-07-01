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
use \mPDF;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A PDF generator plugin for the mPDF library.
 *
 * @PdfGenerator(
 *   id = "mpdf",
 *   module = "pdf_api",
 *   title = @Translation("mPDF"),
 *   description = @Translation("PDF generator using the mPDF generator.")
 * )
 */
class mpdfGenerator extends PdfGeneratorBase implements ContainerFactoryPluginInterface {

  /**
   * The global options for mPDF.
   *
   * @var array
   */
  protected $options = array();

  /**
   * Instance of the mPdf class library.
   *
   * @var \mPdf
   */
  protected $generator;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition, mPDF $generator) {
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
      $container->get('mpdf')
    );
  }
  
  /**
   * {@inheritdoc}
   */
  public function addPage($html) {
    echo "hello";
    $this->generator->addPage($html);
    echo "world";
  }

  /**
   * {@inheritdoc}
   */
  public function setPageOrientation($orientation = PdfGeneratorInterface::PORTRAIT) {
    if($orientation == 'portrait')
      $orientation = 'P';
    else
      $orientation = 'L';
    $this->setOptions(array('orientation' => $orientation));
  }

  /**
   * {@inheritdoc}
   */
  public function setPageSize($page_size) {
    if ($this->isValidPageSize($page_size)) {
      $this->setOptions(array('sheet-size' =>$page_size));
    }
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
    $this->preGenerate();
    $this->generator->WriteHTML($html);
    $this->generator->Output("", "I");
  }

}
