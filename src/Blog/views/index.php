<?= $renderer->render('header')?>

<h1>Bienvenue sur le blog</h1>

<ul>
    <li><a href="<?= $router->generateUri('blog.show', ['slug' => 'zaezae0-8azaeae']); ?>">Article 1</a></li>
    <li>Article1</li>
    <li>Article1</li>
    <li>Article1</li>
</ul>

<?= $renderer->render('footer')?>
