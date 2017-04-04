/**
 * Created by nicolas on 30/03/17.
 */
$(function() {
    $('.menu-tree').each(function() {
        $tree = $(this);
        var randId = $(this).attr('data-rand');
        $tree.tree({
            data: window['data_' + randId],
            dragAndDrop: true,
            autoOpen: 1,
            autoEscape: false,
            onCreateLi: function(node, $li) {
                var currentPosition = $tree.find('li').length + 1;
                // Find menu-tree parent
                $li.find('.jqtree-element')
                    // .text(node.title)
                    .attr('data-url', node.url)
                    .append('<button class="btn btn-default edit-node" data-node-id="' + node.id + '"><i class="glyphicon glyphicon-edit"></i></a>')
                    .append('<button class="btn btn-default delete-node" data-node-id="' + node.id + '"><i class="glyphicon glyphicon-remove-circle"></i></a>')
                ;
            }
        });

        // Logic on items drag and drop
        $tree.bind('tree.move', function(event) {
            // Avoid the move
            event.preventDefault();

            // Manually force the move
            event.move_info.do_move();

            // Regenerate the JSON
            // jsonRegeneration($(event.currentTarget));
            var $input = $("input[type='hidden'][data-rand='" + $(event.currentTarget).attr('data-rand') + "'");
            $input.val($tree.tree('toJson'));
        });

        // Logic on node addition
        $('button.add-node[data-rand="' + randId + '"]').click(function(e) {
            e.preventDefault();

            $container = $tree.parent();

            $tree.tree(
                'appendNode',
                {
                    name: 'Nouvel item',
                    url: 'http://google.fr',
                    id: $tree.find('li').length + 1
                    // owner_type: $container.attr('data-owner-type'),
                    // owner_id: $container.attr('data-owner-id')
                }
            );

            jsonRegeneration($container, randId);
        });

        // Logic on node edition
        $tree.on('click', '.edit-node', function(e) {
            e.preventDefault();

            // Get the node from the tree
            var node = $tree.tree('getNodeById', $(e.currentTarget).attr('data-node-id'));

            $container = $tree.parent();
            $modal = $("#edit-modal-" + randId);

            // update modal item for edition
            $modal.find('input[name="menu-item-title"]').val(node.name);
            $modal.find('input[name="menu-item-target"]').val(node.url);

            // Update tags for node retrieval on modal validation
            $modal.find('button.save').attr('data-rand', randId);
            $modal.find('button.save').attr('data-node-id', node.id);

            $modal.modal('show');
        });

        $('.menu-tree-container').on('click', 'button.save', function(e) {
            $modal = $("#edit-modal-" + randId);
            $modal.modal('hide');
            $container = $tree.parent();

            var node = $tree.tree('getNodeById', $(this).attr('data-node-id'));
            $modal = $("#edit-modal-" + $(this).attr('data-rand'));

            $tree.tree(
                'updateNode',
                node,
                {
                    name: $modal.find('input[name="menu-item-title"]').val(),
                    url: $modal.find('input[name="menu-item-target"]').val()
                }
            );
            jsonRegeneration($container, randId);
        });

        // Logic on node deletion
        $tree.on('click', '.delete-node', function(e) {
            e.preventDefault();

            $container = $tree.parent();

            // Get the id from the 'node-id' data property
            // var $node = $(e.target).closest('li.jqtree_common');
            // $node.remove();

            // Get the node from the tree
            var node = $tree.tree('getNodeById', $(e.currentTarget).attr('data-node-id'));
            $tree.tree('removeNode', node);

            jsonRegeneration($container, randId);
        });
    });

    /**
     * Regenerate JSON
     */
    function jsonRegeneration($container, randId) {

        // Remove all inputs
        // $("input[type='hidden'][data-rand='" + randId + "'").remove();
        //
        // for (var i=0; i < $tree.tree('getTree').children.length; i++) {
        //     var $input = $('<input type="hidden" data-rand="' + $container.attr('data-rand') + '" name="' + $container.attr('data-name') + '[' + i +']" />')
        //     $container.append($input);
        // }
        var $inputs = $("input[type='hidden'][data-rand='" + randId + "'");
        $inputs.val($tree.tree('toJson'));
    }

});