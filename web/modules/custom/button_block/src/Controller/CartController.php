<?php

namespace Drupal\button_block\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\node\Entity\Node;

class CartController extends ControllerBase {

  public function addToCart(Node $node) {
    $uid = \Drupal::currentUser()->id();
    $nid = $node->id();

    $query = \Drupal::database()->select('button_block_data', 'cc')
      ->fields('cc', ['click_count'])
      ->condition('nid', $nid)
      ->condition('uid', $uid)
      ->execute();

    if ($row = $query->fetchAssoc()) {
      $new_count = $row['click_count'] + 1;
      \Drupal::database()->update('button_block_data')
        ->fields(['click_count' => $new_count])
        ->condition('nid', $nid)
        ->condition('uid', $uid)
        ->execute();
    } 
    else {
      \Drupal::database()->insert('button_block_data')
        ->fields(['nid' => $nid, 'uid' => $uid, 'click_count' => 1])
        ->execute();
    }

    // Redirect to product page
    return new RedirectResponse($node->toUrl()->toString());
  }
}