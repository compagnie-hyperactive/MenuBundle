/**
 * Created by nicolas on 30/03/17.
 */
$(function() {
    $('.menu-tree')
        .tree(
            {
                data: data,
                dragAndDrop: true,
                autoOpen: 1,
                autoEscape: false,
                onCreateLi: function(node, $li) {
                    // Find menu-tree parent
                    var $menuTree = $li.closest('.menu-tree');
                    $li.find('.jqtree-element')
                        .text(node.title)
                        .attr('data-url', node.url)
                    ;
                }
            }
        )
    ;
});