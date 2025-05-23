'use strict';

$(document).ready(function () {
    $('#filter_status').select2({
        width: '150px',
    });

    $('#billing_type').select2({
        width: '180px',
    });
});

$(document).on('click', '.delete-btn', function (event) {
    let projectId = $(this).attr('data-id');
    deleteItemLivewire('deleteProject', projectId, Lang.get('messages.contact.project'));
});

window.addEventListener('deleted', function (data) {
    livewireDeleteEventListener(data, Lang.get('messages.contact.project'));
});

$(document).on('change', '#filter_status', function () {
    window.livewire.emit('filterProjectsByStatus', $(this).val());
});

$(document).on('change', '#filterBranch', function () {
    window.livewire.emit('filterBranch', $(this).val());
});
$(document).on('change', '#billing_type', function () {
    window.livewire.emit('filterProjectsByBillingType', $(this).val());
});

$(document).on('mouseenter', '.livewire-card', function () {
    $(this).find('.action-dropdown').removeClass('d-none');
});

// $(document).on('mouseleave', '.livewire-card', function () {
//     $(this).find('.action-dropdown').addClass('d-none');
//     $(this).parent().trigger('click');
// });

document.addEventListener('livewire:load', function (event) {
    Livewire.hook('message.processed', (message, component) => {
        var $owl = $('.owl-carousel');
        $owl.trigger('destroy.owl.carousel');

        $owl.html($owl.find('.owl-stage-outer').html()).
            removeClass('owl-loaded');
        livewireLoadOwel($owl);
    });
});

$(document).ready(function () {
    $('#filterStatus').select2();
});

$(document).on('change', '#filterStatus', function () {
    window.livewire.emit('filterProjectsByStatus', $(this).val());
});

document.addEventListener('DOMContentLoaded', function (event) {
    Livewire.hook('message.received', (message, component) => {
        setTimeout(function () {
            $(document).find('#filterStatus').select2('destroy');
            $(document).find('#filterStatus').select2();
            $(document).find('.select2').removeClass('opacity-0');
        }, 200);
    });
});
