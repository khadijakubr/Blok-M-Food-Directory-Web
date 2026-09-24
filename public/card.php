<div class="card">
    <div class="card-header">
        <h3><?= htmlspecialchars($food['foodName']) ?></h3>
        <span class="rating">
            ⭐ <?= htmlspecialchars($food['rating']) ?> / 10
        </span>
    </div>

    <div class="card-body">
        <p class="price">
            Rp <?= number_format($food['price'], 0, ',', '.') ?>
        </p>
        <p class="location">
            𖡡 <?= htmlspecialchars($food['location']) ?>
        </p>
    </div>

    <div class="card-footer">
        <a href="view.php?id=<?= $id ?>">Details</a>
    </div>
</div>