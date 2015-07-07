<?php

/**
 * @file
 * Contains \Drupal\pdf_api\Plugin\WkhtmltopdfGenerator.
 */

namespace Drupal\pdf_api\Plugin\PdfGenerator;

use Drupal\pdf_api\Plugin\PdfGeneratorBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\pdf_api\Annotation\PdfGenerator;
use Drupal\Core\Annotation\Translation;
use mikehaertl\wkhtmlto\Pdf;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A PDF generator plugin for the WKHTMLTOPDF library.
 *
 * @PdfGenerator(
 *   id = "wkhtmltopdf",
 *   module = "pdf_api",
 *   title = @Translation("WKHTMLTOPDF"),
 *   description = @Translation("PDF generator using the WKHTMLTOPDF binary.")
 * )
 */
class WkhtmltopdfGenerator extends PdfGeneratorBase implements ContainerFactoryPluginInterface {

  /**
   * The global options for WKHTMLTOPDF.
   *
   * @var array
   */
  protected $options = array();

  /**
   * Instance of the WKHtmlToPdf class library.
   *
   * @var \WkHtmlToPdf
   */
  protected $generator;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition, Pdf $generator) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->generator = $generator;
    $this->setOptions(array('binary' => 'C://"Program Files"/wkhtmltopdf/bin/wkhtmltopdf'));
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('wkhtmltopdf')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setHeader($text) {
    $this->setOptions(array('header-right' => $text)); 
  }

  /**
   * {@inheritdoc}
   */
  public function setPageOrientation($orientation = PdfGeneratorInterface::PORTRAIT) {
    $this->setOptions(array('orientation' => $orientation));
  }

  /**
   * {@inheritdoc}
   */
  public function setPageSize($page_size) {
    if ($this->isValidPageSize($page_size)) {
      $this->setOptions(array('page-size' =>$page_size));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function addPage($html) {
    $this->generator->addPage($html);
  }

  /**
   * {@inheritdoc}
   */
  public function setFooter($text) {
    $this->setOptions(array('footer-center' => $text));
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
  public function send($filename=null) {
    $this->preGenerate();
    $this->generator->send($filename);
  }

  /**
   * {@inheritdoc}
   */
  public function stream($html, $filelocation) {
    $this->preGenerate();
    $this->generator->saveAs($filelocation);
  }

  /**
   * Set global options.
   *
   * @param array $options
   *  The array of options to merge into the currently set options.
   */
  protected function setOptions(array $options) {
    $this->options += $options;
  }

  /**
   * Set the global options from the generator plugin into the WKHTMLTOPDF
   * generator class.
   */
  protected function preGenerate() {
    $this->generator->setOptions($this->options);
  }

}
