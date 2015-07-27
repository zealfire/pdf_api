<?php

/**
 * @file
 * Contains \Drupal\pdf_api\Plugin\PdfGeneratorInterface.
 */

namespace Drupal\pdf_api\Plugin;

/**
 * Defines an interface for PDF generator plugins.
 */
interface PdfGeneratorInterface {

  /**
   * Landscape paper orientation.
   */
  const LANDSCAPE = 'landscape';

  /**
   * Portrait paper orientation.
   */
  const PORTRAIT = 'portrait';

  /**
   * Set the various options for PDF.
   *
   * @todo Document the parameters here.
   */
  public function setter($pdf_content, $pdf_location, $save_pdf, $paper_orientation, $paper_size, $footer_content, $header_content);

  /**
   * Returns the administrative id for this generator plugin.
   *
   * @return string
   */
  public function getId();

  /**
   * Returns the administrative label for this generator plugin.
   *
   * @return string
   */
  public function getLabel();

  /**
   * Returns the administrative description for this generator plugin.
   *
   * @return string
   */
  public function getDescription();

  /**
   * Returns instances of PDF libraries.
   *
   * @return object
   */
  // @todo Perhaps rename to getGenerator? That's how it is named in the code.
  public function getObject();

  /**
   * Sets the header in the PDF.
   *
   * @param string $text
   *   The text to rendered as header.
   * @todo Is HTML allowed in $text? If so, consider renaming to html, or document it.
   */
  public function setHeader($text);

  /**
   * Sets the footer in the PDF.
   *
   * @param string $text
   *   The text to rendered as footer.
   * @todo Is HTML allowed in $text? If so, consider renaming to html, or document it.
   */
  public function setFooter($text);

  /**
   * Set the paper orientation of the generated PDF pages.
   *
   * @param PdfGeneratorInterface::PORTRAIT|PdfGeneratorInterface::LANDSCAPE $orientation
   *   The orientation of the PDF pages.
   */
  public function setPageOrientation($orientation = PdfGeneratorInterface::PORTRAIT);

  /**
   * Set the page size of the generated PDF pages.
   *
   * @param string $page_size
   *   The page size (e.g. A4, B2, Letter).
   */
  public function setPageSize($page_size);

  /**
   * Add a page to the generated PDF.
   *
   * @param string $html
   *   The HTML of the page to be added.
   */
  public function addPage($html);

  /**
   * Generate and save the PDF at a specific location.
   *
   * @param string $location
   *   The location path to save the generated PDF to.
   * @todo Absolute path and/or relative to Drupal installation directory,
   *   accepts a Drupal steam wrapper?
   */
  public function save($location);

  /**
   * Send the PDF to the browser as a file download.
   *
   * @param string $filename
   *   The name of the file to be downloaded.
   */
  public function send($filename);

  /**
   * Stream the PDF to the browser.
   *
   * @todo Document the parameters
   */
  public function stream($html, $filelocation);
}
