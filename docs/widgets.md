<h1 style="font-size: 50px; text-align: center;">Views</h1>

## Table of contents
1. [Overview](#overview)
2. [Registering Widgets](#registering-widgets)
3. [Rendering Widgets](#rendering-widgets)
4. [Slots](#slots)
5. [Best Practices](#best-practices)
6. [Related Methods](#related-methods)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Widgets allow you to extend core views without modifying the core view files.
They are small, reusable view blocks (like cards, graphs, or stats) that can be dynamically registered and rendered.

<br>

📁 Location
```bash
resources/views/widgets/
```

<br>

**Making your own widget**
Create a new widget using the following command:
```sh
php console make:widget ${widget-name}
```

<br>

Organize your widgets by feature for clarity, for example:
```bash
resources/views/widgets/
└── dashboard/
    ├── revenueCard.php
    ├── userGrowth.php
    └── activeSessions.php
```

<br>

✅ Common Use Cases
- Add dashboard cards for analytics, stats, or quick actions.
- Inject plugin-specific panels into existing admin pages.
- Keep customizations separate from core framework views.

<br>

## 2. ⚙️ Registering Widgets <a id="registering-widgets"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
You can register widgets in your controller or service before rendering the view:
```php
$this->addWidget(
    'dashboard.cards',                      // Slot name
    'dashboard.revenueCard',       // Path to view file (without .php)
    ['revenue' => 10350]                    // Data passed to the widget
);
```

You can register multiple widgets in the same slot:
```php
$this->addWidget('dashboard.cards', 'dashboard.userGrowth', ['users' => $users]);
$this->addWidget('dashboard.cards', 'dashboard.activeSessions', ['sessions' => $sessions]);
```

<br>

## 3. 🎨 Rendering Widgets <a id="registering-widgets"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
**Render all widgets in a slot:**

Place this in your view where you want all registered widgets for a slot to appear:
```php
<?= $this->renderWidgets('dashboard.cards', $this->widgets) ?>
```

<br>

**Render a single widget manually:**

If you want to directly include a specific widget without registering:
```php
<?= $this->renderWidget('dashboard/activeSessions', ['sessions' => $sessions]) ?>
```

<br>

## 4. 📌 Slots <a id="slots"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
A slot is simply a grouping name that organizes widgets in a particular location.
Example slots:
- `dashboard.cards` – for dashboard cards
- `dashboard.stats` – for statistics sections
- `analytics.reports` – for reports sections

<br>

## 5. 🧠 Best Practices <a id="best-practices"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
✅ Keep widgets small and focused (e.g., one widget per feature)
✅ Group widgets by folder (e.g., `dashboard/`)
✅ Pass only the data needed to keep them decoupled
✅ Use `addWidget()` for user-extensible areas, rather than editing core views

<br>

## 6. 🔗 Related Methods <a id="related-methods"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

| Method                               | Description                    |
| ------------------------------------ | ------------------------------ |
| `addWidget($slot, $viewPath, $data)` | Registers a widget to a slot.  |
| `renderWidgets($slot, $widgets)`     | Renders all widgets in a slot. |
| `renderWidget($viewPath, $data)`     | Renders a single widget file.  |

<br>

## 7. ✅ Example <a id="related-methods"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
**Controller**
```php
$this->view->addWidget('dashboard.index', 'dashboard.activeSessions', $sessions);
```

**Location**
```bash
resources/views/widgets/
└── dashboard/
    └── activeSessions.php
```

<br>


**View**
Hook inside index view for AdminDashboard:
```php
<div class="widget-container">
    <?= $this->renderWidgets('dashboard.index', $this->widgets) ?>
</div>
<?php $this->end(); ?>
```

<br>

**activeSessions.php Widget**

```php
<?php use App\Models\Users; ?>
<?php use Core\Lib\Utilities\DateTime; ?>

<h1 class="text-center mt-5">User Sessions</h1>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <th>User Name</th>
        <th>Created</th>
    </thead>
    <tbody>
        <?php foreach($data as $session): ?>
            <tr>
                <td><?= Users::findById($session->user_id)->username ?></td>
                <td><?= DateTime::timeAgo($session->created_at) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
```

By using widgets, you can build a modular, plugin-friendly dashboard that is easy to extend and maintain without touching the framework’s core views.