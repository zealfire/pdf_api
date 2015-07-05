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
   * Sets the header in the PDF.
   */
  public function setHeader();

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
   */
  public function save($location);

  /**
   * Send the PDF to the browser has a file download.
   *
   * @param string $filename
   *   The filename to send the file to the browser with.
   */
  public function send($filename);

  /**
   * Stream the PDF to the browser.
   */
  public function stream($html, $filelocation);

  /**
   * Sets the footer in the PDF.
   */
  public function setFooter();
}
