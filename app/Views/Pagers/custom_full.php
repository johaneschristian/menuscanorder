<?php $pager->setSurroundCount(3) ?>

<nav class="mt-5">
  <ul class="pagination justify-content-center">
    <li class="page-item">
      <a class='page-link <?= $pager->hasPreviousPage() ? "" : "disabled" ?>' href="<?= $pager->getPreviousPage() ?>" tabindex="-1" aria-disabled="<?= lang('Pager.previous') ?>">Previous</a>
    </li>

    <?php foreach ($pager->links() as $link) : ?>
      <li class="page-item <?= $link['active'] ? 'active' : ''?>" aria-current="page">
        <a class="page-link" href="<?= $link['uri'] ?>"><?= esc($link['title']) ?></a>
      </li>
    <?php endforeach ?>
    
    <li class="page-item">
      <a class='page-link <?= $pager->hasNextPage() ? "" : "disabled" ?>' href="<?= $pager->getNextPage() ?>" tabindex="-1" aria-disabled="<?= lang('Pager.previous') ?>">Next</a>
    </li>
  </ul>
</nav>