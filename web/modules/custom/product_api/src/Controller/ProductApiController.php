<?php

namespace Drupal\product_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class ProductApiController extends ControllerBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Constructs a MovieApiController object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   The current user.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, AccountProxyInterface $current_user) {
    $this->entityTypeManager = $entity_type_manager;
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('current_user')
    );
  }

  /**
   * Returns a JSON response with movie nodes data.
   */
  public function getProducts() {
    // Check for authenticated user
    if (!$this->currentUser->isAuthenticated()) {
      throw new AccessDeniedHttpException();
    }

    // Get the node storage
    $node_storage = $this->entityTypeManager->getStorage('node');

    // Load all Product nodes
    $query = $node_storage->getQuery()
      ->condition('type', 'product')
      ->condition('status', 1) // Only published nodes
      ->accessCheck(FALSE) // Disable access checking
      ->execute();

    $nodes = $node_storage->loadMultiple($query);

    // Prepare data to be returned
    $products = [];
    foreach ($nodes as $node) {
      $products[] = [
        'id' => $node->id(),
        'title' => $node->getTitle(),
        'body' => $node->get('body')->value,
        'field_product_price' => $node->get('field_price')->value,
      ];
    }

    // Return JSON response
    return new JsonResponse($products);
  }
}
