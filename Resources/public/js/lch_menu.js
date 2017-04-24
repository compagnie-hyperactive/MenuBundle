/**
 * Created by nicolas on 30/03/17.
 */
$(function() {

    $('.menu-tree').each(function() {
        $tree = $(this);
        var $container = $tree.parent();

        var randId = $(this).attr('data-rand');
        $tree.tree({
            data: window['data_' + randId],
            dragAndDrop: true,
            autoOpen: 1,
            autoEscape: false,
            onCreateLi: function(node, $li) {
                var currentPosition = $tree.find('li').length + 1;

                var urlString = (node.url && node.url != '') ? ' - (<i>' + node.url + '</i>)' : '';

                diplayableTags = jsonStrTagsToStr(node.tags);
                var tagString = (diplayableTags && diplayableTags != '') ? ' - (<b>' + diplayableTags + '</b>)' : '';

                // Find menu-tree parent
                $li.find('.jqtree-element')
                    .html("<i class='glyphicon glyphicon-option-vertical'></i>" + $li.find('.jqtree-element').text() + urlString + tagString)
                    .attr('data-url', node.url)
                    .append('' +
                        '<div class="buttons">' +
                            '<button class="btn btn-default edit-node" data-node-id="' + node.id + '">' +
                                '<i class="glyphicon glyphicon-edit"></i>' +
                            '</button>' +
                            '<button class="btn btn-default delete-node" data-node-id="' + node.id + '">' +
                            '   <i class="glyphicon glyphicon-remove-circle"></i>' +
                            '</button>' +
                        '</div>')
                ;
            },
            closedIcon: $('<i class="glyphicon glyphicon-circle-arrow-right"/>'),
            openedIcon: $('<i class="glyphicon glyphicon-circle-arrow-down"/>')
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

            // $container = $tree.parent();

            $tree.tree(
                'appendNode',
                {
                    name: 'Nouvel item',
                    url: 'http://google.fr',
                    id: $tree.find('li').length + 1,
                    tags: ""
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

            // $container = $tree.parent();
            $modal = $("#edit-modal-" + randId);

            // update modal item for edition
            $modal.find('input[name="menu-item-title"]').val(node.name);
            $modal.find('input[name="menu-item-target"]').val(node.url);
            $modal.find('input[name="menu-item-tags"]').val(jsonStrTagsToStr(node.tags));

            // Update tags for node retrieval on modal validation
            $modal.find('button.save').attr('data-rand', randId);
            $modal.find('button.save').attr('data-node-id', node.id);

            $modal.modal('show');
        });

        $('.menu-tree-container').on('click', 'button.save', function(e) {
            $modal = $("#edit-modal-" + randId);
            $modal.modal('hide');
            // $container = $tree.parent();

            var node = $tree.tree('getNodeById', $(this).attr('data-node-id'));
            $modal = $("#edit-modal-" + $(this).attr('data-rand'));

            $tree.tree(
                'updateNode',
                node,
                {
                    name: $modal.find('input[name="menu-item-title"]').val().trim(),
                    url: $modal.find('input[name="menu-item-target"]').val().trim(),
                    tags: strTagsToJson($modal.find('input[name="menu-item-tags"]').val()),
                }
            );
            console.log(node);
            jsonRegeneration($container, randId);
        });

        // Logic on node deletion
        $tree.on('click', '.delete-node', function(e) {
            e.preventDefault();

            // Get the id from the 'node-id' data property
            // var $node = $(e.target).closest('li.jqtree_common');
            // $node.remove();

            // Get the node from the tree
            var node = $tree.tree('getNodeById', $(e.currentTarget).attr('data-node-id'));
            $tree.tree('removeNode', node);

            jsonRegeneration($container, randId);
        });

        $('button[type="submit"]').click(function(e) {
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
        $inputs.val($tree.tree('toJson').replace("'", "\'"));
    }

    function strTagsToJson(strTags){

        var arrayTags = strTags.split(',');

        array = $.map(arrayTags, function(tags) {
            return tags.trim();
        });

        return array;
    }

    function jsonStrTagsToStr(tags){
        try {
            jsontags = JSON.parse(tags);
        } catch (e) {
            return tags;
        }
        return jsonTags;

    }
});