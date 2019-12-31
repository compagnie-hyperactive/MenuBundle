# MenuBundle

This provides a simple handling for menu items, based on Wordpress idea. 
You define __menu locations__, and then you retrieve menu items assigned on this location using a Twig extension.

For item editing part in BO, the bundle provides a `MenuType` and CSS/JS part for drag-n drop feature.

This bundle is translatable, using [lch/translatable-bundle](https://github.com/compagnie-hyperactive/TranslateBundle). 

## Installation

`composer require lch/menu-bundle "^1.1.8"`

## Configuration

After installing, create a `lch_menu.yaml` in `config/packages`. You can now defines your __menus locations__, for example :

```yaml
lch_menu:
  locations:
    header:
      title: Header
    main:
      title: Main navigation
    footer_buttons:
      title: Footer - buttons
    footer_left:
      title: Footer - left
# ...
```

## Admin part

This is entirely up to you, but you have to use `Lch\MenuBundle\Entity\Menu` class on you CRUD. CRUD example below :

```php
namespace App\Controller\Admin;

use App\Form\Type\Extension\MenuTypeExtension;
use App\Repository\MenuRepository;
use Lch\MenuBundle\Entity\Menu;
use Lch\MenuBundle\Form\MenuType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/admin/menu")
 * @IsGranted("ROLE_ADMIN")
 */
class MenuController extends AbstractController
{
    /**
     * @Route(
     *     "/{page}",
     *     name="admin.menu.list",
     *     requirements={"page"="[1-9]\d*"},
     *     defaults={"page"=1}
     * )
     *
     * @param int $page
     *
     * @return Response
     */
    public function list(int $page): Response
    {
        $nbItemsPerPage = 20;

        /** @var MenuRepository $r */
        $r = $this->getDoctrine()->getRepository(Menu::class);
        $menus = $r->getPaginatedList($page, $nbItemsPerPage, [], [], true);

        return $this->render('admin/menu/list.html.twig', [
            'menus' => $menus,
            'pagination' => [
                'page' => $page,
                'nbPages' => ceil($menus->count() / $nbItemsPerPage)
            ]
        ]);
    }

    /**
     * @Route("/create", name="admin.menu.create")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request): Response
    {
        return $this->edit(new Menu(), $request);
    }

    /**
     * @Route("/edit/{id}", name="admin.menu.edit")
     *
     * @param Menu $menu
     * @param Request $request
     *
     * @return Response
     */
    public function edit(Menu $menu, Request $request): Response
    {
        // Use the MenuType from bundle
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush();
            if ($form->get('save')->get('save')->isClicked()) {
                return $this->redirectToRoute('admin.menu.list');
            }
            return $this->redirectToRoute('admin.menu.edit', ['id' => $menu->getId()]);
        }

        return $this->render('admin/menu/edit.html.twig', [
            'menu' => $menu,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/delete/{id}", name="admin.menu.delete")
     * @Method({"GET", "DELETE"})
     *
     * @param Menu $menu
     * @param Request $request
     * @return Response
     */
    public function delete(Menu $menu, Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->setMethod('DELETE')
            ->add('delete', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-danger'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($menu);
            $em->flush();

            return $this->redirectToRoute('admin.menu.list');
        }

        return $this->render('admin/menu/delete.html.twig', [
            'menu' => $menu,
            'form' => $form->createView()
        ]);
    }
}
```

## Drag'n drop part

You must include CSS and JS components in your Webpack admin entrypoint :

```javascript
// ...
require('../../vendor/lch/menu-bundle/Resources/public/css/lch_menu.css');
require('../../vendor/lch/menu-bundle/Resources/public/js/lch_menu');
//...
```
## Screenshots

Using code above, this can provide following presentation :
![Menu listing](https://compagnie-hyperactive.github.io/MenuBundle/images/listing.png)


![Menu editing](https://compagnie-hyperactive.github.io/MenuBundle/images/editing.png)

## Menu item composition

Each menu item is composed with a title, a link and a field called __technical tags__. This last one is to be used
for carrying any context you would latter need on presentation. This bundle provides only normalization stage
on menu items, it up to the template to create presentation that suits to your needs.

_note : in the screenshot above, technical tags carry an SVG icon name._

## Menu retrieval

To get menus data, you must use `get_menu_items` Twig extension : 

```twig
{% for menuItem in get_menu_items('header', locale) %}
    <div class="c-header__pre__right__faq">
        <a href="{{ menuItem.url }}">
            {% if(menuItem.tags|length > 0) %}
                <svg class="icon-faq" viewBox="0 0 100 100">
                    <use xlink:href="#icon-{{ menuItem.tags[0] }}"></use>
                </svg>
            {% endif %}
            {{ menuItem.name }}
        </a>
    </div>
{% endfor %}
```
 

