<?php

namespace Drupal\smart_trim_readmore\Truncate;

use Drupal\smart_trim\TruncateHTML;

/**
 * Class ExtraTruncateHTML.
 */
class ExtraTruncateHTML extends TruncateHTML {

  /**
   * Indicates if the truncate has been applied.
   *
   * @var bool
   */
  protected $truncated = FALSE;

  /**
   * {@inheritdoc}
   */
  public function truncateChars(string $html, int $limit, string $ellipsis = '...'): string {
    $result = parent::truncateChars($html, $limit, $ellipsis);
    $this->truncated = isset($this->limit);
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function truncateWords(string $html, int $limit, string $ellipsis = '...'): string {
    $result = parent::truncateWords($html, $limit, $ellipsis);
    $this->truncated = isset($this->limit);
    return $result;
  }

  /**
   * Checks if the truncate has been applied.
   *
   * @return bool
   */
  public function isTruncated() {
    return $this->truncated;
  }

}
