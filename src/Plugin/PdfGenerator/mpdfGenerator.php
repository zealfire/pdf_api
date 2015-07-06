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
  public function setHeader() {
    $this->generator->SetHeader('{PAGENO}'); 
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
  * Sets the password in PDF.
  *
  * @param string $password
  *   The password which will be used in PDF.
  */
  public function setPassword($password) {
    if (isset($password) && $password != NULL) {
      // Print and Copy is allowed.
      $this->generator->SetProtection(array('print', 'copy'), $password, $password);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setFooter() {
    $this->generator->SetFooter('{PAGENO} / {nb}');
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
    $stylesheet = '.node_view  { display: none; }';
    $this->generator->WriteHTML($stylesheet, 1);
    $this->generator->WriteHTML($html, 0);
    $this->generator->Output("", "I");
  }

  /**
   * {@inheritdoc}
   */
  public function stream($html, $filelocation) {
    $this->preGenerate();
    // This way you can add css file too.
    $stylesheet = '.node_view  { display: none; }';
    $this->generator->WriteHTML($stylesheet, 1);
    $this->generator->WriteHTML($html, 0);
    $this->generator->Output($filelocation, 'F');
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
   * Set the global options from the generator plugin into the mPDF
   * generator class.
   */
  protected function preGenerate() {
    $this->generator->AddPageByArray($this->options);
  }

}
