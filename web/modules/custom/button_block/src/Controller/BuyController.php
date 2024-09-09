<?php

namespace Drupal\button_block\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\node\Entity\Node;

class BuyController extends ControllerBase {

  public function buyCart(Node $node) {
    $uid = \Drupal::currentUser()->id();
    $nid = $node->id();

    $query = \Drupal::database()->select('button_block_data', 'cc')
      ->fields('cc', ['click_count'])
      ->condition('nid', $nid)
      ->condition('uid', $uid)
      ->execute();
    $build['content'] = [
        '#theme' => 'buy_dashboard',
        '#rows' => $query,
    ];
    return $build;
  }
}