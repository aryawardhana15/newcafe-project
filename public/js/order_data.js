import { previewImage } from "./image_preview.js";

// modal order detail
$("span.order-detail-link[title='order detail']").click(function (event) {
    setVisible("#loading", true);
    var id = $(this).attr("data-id");

    $.ajax({
        url: "/order/data/" + id,
        method: "get",
        dataType: "json",
        success: function (response) {
            const date = new Date(response["created_at"]).toLocaleDateString(
                "id-id",
                {
                    weekday: "long",
                    year: "numeric",
                    month: "short",
                    day: "numeric",
                }
            );

            $("#username_detail").html("@" + response["user"]["username"]);
            $("#order_date_detail").html(date);
            $("#quantiity_detail").html(response["quantity"]);
            $("#address_detail").html(response["address"]);
            $("#payment_method_detail").html(
                response["payment"]["payment_method"]
            );
            $("#status_detail").html(response["status"]["order_status"]);
            $("#style_status_detail")
                .removeClass()
                .addClass(
                    "spinner-grow spinner-grow-sm text-" +
                        response["status"]["style"]
                );
            $("#bank_detail").html(
                response["bank"] ? response["bank"]["bank_name"] : ""
            );
            $("#account_number_detail").html(
                response["bank"] ? response["bank"]["account_number"] : ""
            );
            $("#notes_transaction_detail").html(
                response["refusal_reason"]
                    ? response["refusal_reason"]
                    : response["note"]["order_notes"]
            );
            $("#total_price_detail").html(response["total_price"]);

            if (response["payment_id"] == 1) {
                $("#transaction_doc_detail").attr(
                    "src",
                    "/storage/" + response["transaction_doc"]
                );
            }

            $("#product_name_detail").html(response["product"]["product_name"]);
            $("#image_product_detail").attr(
                "src",
                "/storage/" + response["product"]["image"]
            );
            $("#link_bukti_transfer").attr("data-id", response["id"]);
            $("#form_cancel_order").attr(
                "action",
                "/order/cancel_order/" + response["id"]
            );

            // transfer proof url
            $("#link_transfer_proof").attr(
                "data-imageUrl",
                "/storage/" + response["transaction_doc"]
            );

            // reject order form
            $("#form_reject_order").attr(
                "action",
                "/order/reject_order/" +
                    response["id"] +
                    "/" +
                    response["product_id"]
            );

            // end order form
            $("#form_end_order").attr(
                "action",
                "/order/end_order/" +
                    response["id"] +
                    "/" +
                    response["product_id"]
            );

            // approve order form
            $("#form_approve_order").attr(
                "action",
                "/order/approve_order/" +
                    response["id"] +
                    "/" +
                    response["product_id"]
            );

            // edit order
            $("#link_edit_order").attr(
                "href",
                "/order/edit_order/" + response["id"]
            );

            if (response["coupon_used"] != null) {
                $("#content-kuponUsed").html(
                    `<span class="link-danger" style="cursor: pointer; ">` +
                        response["coupon_used"] +
                        ` kupon</span> digunakan untuk pemesanan ini`
                );
            } else {
                $("#content-kuponUsed").html(
                    `tidak ada kupon yang digunakan untuk pemesanan ini`
                );
            }

            // restrict proof of transfer for COD payment method
            if (response["payment"]["payment_method"] == "COD") {
                // menghilangkan element sesuai metode pembayaran
                $("#modal_section_payment_proof").css("display", "none");
                $("#row_bank").css("display", "none");
            } else {
                // to restore undisplayed elements
                $("#modal_section_payment_proof").css("display", "unset");
                $("#row_bank").css("display", "table-row");
            }

            // if order has been canceled by user
            if (response["status_id"] == 5) {
                console.log("benerkah");
                $("#link_edit_order > button").css("display", "none");
                $("#form_cancel_order > button").css("display", "none");
                $("#message").css("display", "unset");
                $("#message").html("Order has been canceled by user");
            } else if (response["status_id"] == 3) {
                // if order has been rejected by admin
                $("#link_edit_order").css("display", "none");
                $("#form_cancel_order").css("display", "none");
                $("#message").css("display", "unset");
                $("#message").html("Order has been rejected by admin");
            } else {
                // to restore undisplayed elements
                $("#link_edit_order > button").css("display", "unset");
                $("#form_cancel_order > button").css("display", "unset");
                $("#message").css("display", "none");
            }

            // considered the listener on reject order
            $("#message_reject_order").css("display", "none");
            $("#message_reject_order").html();

            $("#OrderDetailModal").modal("show");
            setVisible("#loading", false);
        },
    });
});

const setVisible = (elementOrSelector, visible) =>
    ((typeof elementOrSelector === "string"
        ? document.querySelector(elementOrSelector)
        : elementOrSelector
    ).style.display = visible ? "block" : "none");

$("a.uploadProof[title='Upload Transfer Proof']").click(function (event) {
    setVisible("#loading", true);
    var id = $(this).attr("data-id");

    $.ajax({
        url: "/order/getProof/" + id,
        method: "get",
        dataType: "json",
        success: function (response) {
            $("#form_upload_proof").attr(
                "action",
                "/order/upload_proof/" + response["id"]
            );
            $("#old_image_proof").val(
                "/storage/" + response["transaction_doc"]
            );

            $("#image_review_upload").attr(
                "src",
                response["transaction_doc"] !=
                    "proof/fmg7fWMmb7mNvnHHA70IlRXxRF4wsD9J6dQAUZkV.png"
                    ? "/storage/" + response["transaction_doc"]
                    : "/storage/" +
                          "proof/fmg7fWMmb7mNvnHHA70IlRXxRF4wsD9J6dQAUZkV.png"
            );
            $("#message_upload_proof").html(
                $("#image_review_upload").attr("src") !=
                    "/storage/proof/fmg7fWMmb7mNvnHHA70IlRXxRF4wsD9J6dQAUZkV.png"
                    ? "image selected"
                    : "no selected image"
            );

            if (response["status_id"] == "2") {
                $("#ProofUploadModal").modal("show");
            } else if (response["status_id"] != "4") {
                Swal.fire(
                    `Action denied, order is already ${
                        response["status"]["order_status"]
                    } by ${response["status_id"] != "5" ? "admin" : "you"}`
                );
            }

            setVisible("#loading", false);
        },
    });
});

$("#image_upload_proof").on("change", function () {
    previewImage({
        image: "image_upload_proof",
        image_preview: "image_review_upload",
        image_preview_alt: "transfer proof",
    });
});

$("#form_upload_proof").submit(function (event) {
    if (
        $("#old_image_proof").val() != $("#image_upload_proof").val() &&
        $("#image_upload_proof").val() != ""
    ) {
        return;
    }

    $("#message_upload_proof").html("Please select an image");
    $("#message_upload_proof").css("color", "red");

    event.preventDefault();
});

$("#link_transfer_proof").click(function () {
    Swal.fire({
        title: "Transfer Proof",
        imageUrl: $("#link_transfer_proof").attr("data-imageUrl"),
        imageWidth: 600,
        imageHeight: 350,
        imageAlt: "Transfer Proof",
    });
});

// cancel order [customer]
$("#button_cancel_order").click(function (e) {
    e.preventDefault();
    Swal.fire({
        title: "Are you sure?",
        text: "after this process, this order is no longer valid and can't be change",
        icon: "question",
        confirmButtonText: "Confirm",
        cancelButtonColor: "#d33",
        showCancelButton: true,
        confirmButtonColor: "#08a10b",
        timer: 10000,
    }).then((result) => {
        if (result.isConfirmed) {
            setVisible("#loading", true);
            $("#OrderDetailModal").modal("hide");

            $("#form_cancel_order").submit();
        } else if (result.isDismissed) {
            Swal.fire("Action canceled", "", "info");
        }
    });
});

// approve order [admin]
$("#button_approve_order").click(function (e) {
    e.preventDefault();
    Swal.fire({
        title: "Are you sure?",
        text: "the order will be considered valid and ready to be handed over to the customer",
        icon: "question",
        confirmButtonText: "Confirm",
        cancelButtonColor: "#d33",
        showCancelButton: true,
        confirmButtonColor: "#08a10b",
        timer: 10000,
    }).then((result) => {
        if (result.isConfirmed) {
            setVisible("#loading", true);
            $("#OrderDetailModal").modal("hide");

            $("#form_approve_order").submit();
        } else if (result.isDismissed) {
            Swal.fire("Action canceled", "", "info");
        }
    });
});

// end order [admin]
$("#button_end_order").click(function (e) {
    e.preventDefault();
    Swal.fire({
        title: "Are you sure?",
        text: "after the order is completed, this data will be considered as company income",
        icon: "question",
        confirmButtonText: "Confirm",
        cancelButtonColor: "#d33",
        showCancelButton: true,
        confirmButtonColor: "#08a10b",
        timer: 10000,
    }).then((result) => {
        if (result.isConfirmed) {
            setVisible("#loading", true);
            $("#OrderDetailModal").modal("hide");

            $("#form_end_order").submit();
        } else if (result.isDismissed) {
            Swal.fire("Action canceled", "", "info");
        }
    });
});

// reject order [admin]
$("#button_reject_order").click(function (e) {
    e.preventDefault();

    if ($("#refusal_reason").val() == "") {
        console.log("hms");
        $("#message_reject_order").html("Refusal reason cannot be empty");
        $("#message_reject_order").css("display", "unset");
        return;
    }

    Swal.fire({
        title: "Are you sure?",
        text: "after this process, this order is no longer valid and can't be change",
        icon: "question",
        confirmButtonText: "Confirm",
        cancelButtonColor: "#d33",
        showCancelButton: true,
        confirmButtonColor: "#08a10b",
        timer: 10000,
    }).then((result) => {
        if (result.isConfirmed) {
            setVisible("#loading", true);
            $("#ModalRejectOrder").modal("hide");

            $("#form_reject_order").submit();
        } else if (result.isDismissed) {
            Swal.fire("Action canceled", "", "info");
            $("#ModalRejectOrder").modal("hide");
            $("#OrderDetailModal").modal("show");
        }
    });
});

// Handle Upload Proof Button Click
$('.upload-proof').click(function() {
    const orderId = $(this).data('order-id');
    
    // Set form action URL
    $('#uploadProofForm').attr('action', `/order/upload_proof/${orderId}`);
    
    // Reset form and preview
    $('#uploadProofForm')[0].reset();
    $('#image_preview').attr('src', '').addClass('d-none');
    $('#old_image_proof').val(defaultImageProof);
});

// Preview Image Before Upload
$('#image_upload_proof').change(function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#image_preview')
                .attr('src', e.target.result)
                .removeClass('d-none');
        }
        reader.readAsDataURL(file);
    } else {
        $('#image_preview')
            .attr('src', '')
            .addClass('d-none');
    }
});

// Form Validation
$('#uploadProofForm').submit(function(e) {
    const fileInput = $('#image_upload_proof');
    if (!fileInput.val()) {
        e.preventDefault();
        alert('Silakan pilih file gambar terlebih dahulu');
        return false;
    }
    
    // Show loading state
    const submitBtn = $(this).find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...');
    
    return true;
});

// Order Data Page Functionality
document.addEventListener('alpine:init', () => {
    Alpine.data('orderData', () => ({
        orders: [],
        filteredOrders: [],
        searchQuery: '',
        sortField: null,
        sortDirection: 'asc',
        selectedStatus: 'all',
        
        init() {
            // Initialize orders from the server-rendered data
            this.orders = Array.from(document.querySelectorAll('tbody tr')).map(row => ({
                id: row.querySelector('td:first-child').textContent.trim().replace('#', ''),
                customer: row.querySelector('td:nth-child(3)')?.textContent.trim() || '',
                totalPrice: parseFloat(row.querySelector('td:nth-child(5)').textContent.trim().replace('Rp ', '').replace(/\./g, '')),
                status: row.querySelector('td:nth-child(7) span').textContent.trim(),
                statusId: row.getAttribute('data-status-id')
            }));
            
            this.filteredOrders = [...this.orders];
            this.initializeSearch();
            this.initializeSorting();
        },
        
        initializeSearch() {
            const searchInput = document.querySelector('input[type="text"]');
            searchInput.addEventListener('input', (e) => {
                this.searchQuery = e.target.value.toLowerCase();
                this.filterOrders();
            });
        },
        
        initializeSorting() {
            const headers = document.querySelectorAll('th.group');
            headers.forEach(header => {
                header.addEventListener('click', () => {
                    const field = header.textContent.trim().toLowerCase().replace(' ', '_');
                    this.sort(field);
                });
            });
        },
        
        filterOrders() {
            this.filteredOrders = this.orders.filter(order => {
                const matchesSearch = 
                    order.id.toLowerCase().includes(this.searchQuery) ||
                    order.customer.toLowerCase().includes(this.searchQuery) ||
                    order.status.toLowerCase().includes(this.searchQuery);
                    
                const matchesStatus = this.selectedStatus === 'all' || order.statusId === this.selectedStatus;
                
                return matchesSearch && matchesStatus;
            });
            
            if (this.sortField) {
                this.sort(this.sortField, this.sortDirection, false);
            }
            
            this.updateTable();
        },
        
        sort(field, direction = null, toggle = true) {
            if (toggle && field === this.sortField) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortField = field;
                this.sortDirection = direction || 'asc';
            }
            
            this.filteredOrders.sort((a, b) => {
                let comparison = 0;
                
                switch (field) {
                    case 'order_id':
                        comparison = a.id.localeCompare(b.id, undefined, { numeric: true });
                        break;
                    case 'customer':
                        comparison = a.customer.localeCompare(b.customer);
                        break;
                    case 'total_price':
                        comparison = a.totalPrice - b.totalPrice;
                        break;
                    default:
                        return 0;
                }
                
                return this.sortDirection === 'asc' ? comparison : -comparison;
            });
            
            this.updateTable();
            this.updateSortIndicators(field);
        },
        
        updateTable() {
            const tbody = document.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            rows.forEach(row => {
                const orderId = row.querySelector('td:first-child').textContent.trim().replace('#', '');
                const order = this.filteredOrders.find(o => o.id === orderId);
                row.style.display = order ? '' : 'none';
            });
        },
        
        updateSortIndicators(field) {
            const headers = document.querySelectorAll('th.group');
            headers.forEach(header => {
                const icon = header.querySelector('i.fas');
                const headerField = header.textContent.trim().toLowerCase().replace(' ', '_');
                
                if (headerField === field) {
                    icon.className = `fas fa-sort-${this.sortDirection === 'asc' ? 'up' : 'down'}`;
                    icon.classList.remove('text-gray-400');
                    icon.classList.add('text-blue-500');
                } else {
                    icon.className = 'fas fa-sort text-gray-400';
                }
            });
        }
    }));
});

// Handle file upload preview
function handleFileSelect(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    // Validate file type
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!allowedTypes.includes(file.type)) {
        Toast.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hanya file gambar yang diperbolehkan (JPG, PNG, GIF)'
        });
        event.target.value = '';
        return;
    }
    
    // Validate file size (max 2MB)
    if (file.size > 2 * 1024 * 1024) {
        Toast.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ukuran file maksimal 2MB'
        });
        event.target.value = '';
        return;
    }
    
    // Preview image
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.querySelector('#imagePreview');
        preview.src = e.target.result;
        preview.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}

// Handle drag and drop
function handleDragOver(event) {
    event.preventDefault();
    event.currentTarget.classList.add('border-blue-500', 'bg-blue-50');
}

function handleDragLeave(event) {
    event.preventDefault();
    event.currentTarget.classList.remove('border-blue-500', 'bg-blue-50');
}

function handleDrop(event) {
    event.preventDefault();
    event.currentTarget.classList.remove('border-blue-500', 'bg-blue-50');
    
    const file = event.dataTransfer.files[0];
    if (file) {
        const input = document.querySelector('#proofFile');
        input.files = event.dataTransfer.files;
        handleFileSelect({ target: input });
    }
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.forEach(function(tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Export functionality
window.exportToExcel = function() {
    const table = document.querySelector('table');
    const wb = XLSX.utils.table_to_book(table, { sheet: "Orders" });
    XLSX.writeFile(wb, `orders_${new Date().toISOString().split('T')[0]}.xlsx`);
}

// Print functionality
window.printOrders = function() {
    window.print();
}
