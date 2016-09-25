/**
 * Created by ng on 16/9/24.
 */

(function ($) {
    var formData = [];

    var displayForwardAndBackwardButton = function () {
        var $relatedEntityCards = $('.related-entity-card');

        var lengthOfRelatedEntityCards = $relatedEntityCards.length;
        for (var i = 0; i < lengthOfRelatedEntityCards; ++i) {
            var card = $relatedEntityCards[i];

            if (i == 0) {
                $(card).find('.rr-backward').addClass('rr-disabled');
            } else {
                $(card).find('.rr-backward').removeClass('rr-disabled');
            }
            if (i == lengthOfRelatedEntityCards - 1) {
                $(card).find('.rr-forward').addClass('rr-disabled');
            } else {
                $(card).find('.rr-forward').removeClass('rr-disabled');
            }
        }
    };

    var swapCard = function () {
        var $btn = $(this);
        var $card = $btn.parents('.related-entity-card');

        if ($btn.is('.rr-backward')) {
            $card.prev('.related-entity-card').before($card);
        } else {
            $card.next('.related-entity-card').after($card);
        }

        displayForwardAndBackwardButton();
        buildFormData();
    };

    var buildFormData = function () {
        formData = [];
        $('.related-entity-card').each(function () {
            formData.push($(this).data('id'));
        });
        console.log(formData);
        $('#' + CONFIG.inputId).val(JSON.stringify(formData));
    };

    var fetchRelatedRecords = function() {
        var $this = $(this);
        var page = $this.data('page') || 1;

        var excludeIds = [];
        excludeIds.push(CONFIG.recordId);

        for (var i = 0; i < formData.length; ++i) {
            excludeIds.push(formData[i]);
        }

        $(this).popup({
            handler: 'onLoadRelatedRecords',
            extraData: {
                page:           page,
                excludeIds:     excludeIds,
                recordsPerPage: CONFIG.recordsPerPage,
                modelClass:     CONFIG.modelClass,
                whereClause:    CONFIG.whereClause,
                titleField:     CONFIG.titleField,
                imageField:     CONFIG.imageField,
                contentField:   CONFIG.contentField
            }
        });
    };

    var buildCard = function(id, title, image, content) {
        var html = ''+
            '<div class="col s12 m3 related-entity-card" data-id="' + id + '">' +
            '    <div class="card small">' +
            '        <div class="card-image">' +
            '            <img src="' + image + '">' +
            '            <span class="card-title">' + title + '</span>' +
            '        </div>' +
            '        <div class="card-content">' +
            content +
            '        </div>' +
            '        <div class="card-action">' +
            '            <table style="width: 100%;text-align: center;">' +
            '                <tr>' +
            '                    <td>' +
            '                        <a href="javascript:void(0)" style="margin-right: 0;" class="rr-backward">' +
            '                            <i class="icon-chevron-left"></i>' +
            '                        </a>' +
            '                    </td>' +
            '                    <td>' +
            '                        <a href="javascript:void(0)" style="margin-right: 0;" class="rr-remove">移除</a>' +
            '                    </td>' +
            '                    <td>' +
            '                        <a href="javascript:void(0)" style="margin-right: 0;" class="rr-forward">' +
            '                            <i class="icon-chevron-right"></i>' +
            '                        </a>' +
            '                    </td>' +
            '                </tr>' +
            '            </table>' +
            '        </div>' +
            '    </div>' +
            '</div>';
        return html;
    };

    var addRelatedRecord = function () {
        var $this = $(this);
        var id = $this.data('id');
        var title = $this.data('title');
        var image = $this.data('image') || (CONFIG.widgetDir + '/assets/img/default.png');
        var content = $this.data('content');
        var cardHtml = buildCard(id, title, image, content);
        $(cardHtml).insertBefore($('.related-entity-control-card'));
        buildFormData();
        displayForwardAndBackwardButton();

        $(document).find('.rr-modal-close').each(function () {
            $(this).trigger('click');
        });
    };

    var removeRelatedRecord = function () {
        var $this = $(this);
        $this.parents('.related-entity-card').remove();
        buildFormData();
        displayForwardAndBackwardButton();
    };


    $(document).ready(function () {
        var $relatedEntities = $('#related-entities');
        displayForwardAndBackwardButton();
        buildFormData();
        $relatedEntities.on('click', '.rr-backward', swapCard);
        $relatedEntities.on('click', '.rr-forward', swapCard);
        $relatedEntities.on('click', '.rr-add', fetchRelatedRecords);
        $relatedEntities.on('click', '.rr-remove', removeRelatedRecord);
        $(document).on('click', '.rr-add-related', addRelatedRecord);
        $(document).on('click', '.rr-pagination', fetchRelatedRecords);

        $(document).on('ajaxSuccess', '.rr-pagination', function (ev, ctx, data, status, jXhr) {
            console.log(ev, ctx, data, status, jXhr);
            var $thisRRModalClose = $(this).parents('.rr-modal-close');
            $(document).find('.rr-modal-close').each(function () {
                if ($(this) != $thisRRModalClose) {
                    $(this).trigger('click');
                }
            });
        });
    });
})(jQuery);