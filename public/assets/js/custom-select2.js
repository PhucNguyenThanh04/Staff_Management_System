function setupSelect2({
    target,
    searchUrl,
    placeholder = 'Tìm kiếm...',
    selectedItem = null,
    data = function (params) {
        return {
            q: params.term // Term là từ khóa mà người dùng nhập
        };
    },
    processResults = function (data) {
        return {
            results: data.data.map(item => ({
                id: '',
                text: ''
            }))
        };
    }
}) {
    $(target).select2({
        dropdownParent: $(target).parent(),
        placeholder: placeholder,
        ajax: {
            url: searchUrl, // URL API của bạn
            dataType: 'json',
            delay: 250, // Giảm tải server với độ trễ
            data: data,
            processResults: processResults,
            cache: true
        },
        minimumInputLength: 1 // Số ký tự tối thiểu trước khi tìm kiếm
    });
    if (selectedItem) {
        const option = new Option(selectedItem.text, selectedItem.id, true, true);
        $(target).append(option).trigger('change');
    }
}