// File: Script untuk resources/views/admin/reports/index.blade.php (dalam @push('scripts'))

document.addEventListener("DOMContentLoaded", function () {
    const csrfToken =
        document.querySelector('meta[name="csrf-token"]')?.content || "";

    if (!csrfToken) {
        console.error("CSRF token not found in meta tag.");
    }

    // ===========================
    // 1. FILTER & SEARCH FUNCTIONS
    // ===========================

    const searchInput = document.getElementById("searchReport");
    const filterDateFrom = document.getElementById("filterDateFrom");
    const filterDateTo = document.getElementById("filterDateTo");
    const filterLokasi = document.getElementById("filterLokasi");
    const filterStatus = document.getElementById("filterStatus");
    const resetFilterBtn = document.getElementById("resetFilter");

    // Search filter
    if (searchInput) {
        searchInput.addEventListener("keyup", filterTable);
    }

    // Date filters
    if (filterDateFrom) {
        filterDateFrom.addEventListener("change", filterTable);
    }
    if (filterDateTo) {
        filterDateTo.addEventListener("change", filterTable);
    }

    // Lokasi filter
    if (filterLokasi) {
        filterLokasi.addEventListener("change", filterTable);
    }

    // Status filter
    if (filterStatus) {
        filterStatus.addEventListener("change", filterTable);
    }

    // Reset filter button
    if (resetFilterBtn) {
        resetFilterBtn.addEventListener("click", function () {
            searchInput.value = "";
            filterDateFrom.value = "";
            filterDateTo.value = "";
            filterLokasi.value = "";
            filterStatus.value = "";
            filterTable();
        });
    }

    // Combined filter function
    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const dateFrom = filterDateFrom.value;
        const dateTo = filterDateTo.value;
        const lokasiValue = filterLokasi.value;
        const statusValue = filterStatus.value;
        const tableRows = document.querySelectorAll("tbody tr");

        let visibleCount = 0;

        tableRows.forEach((row) => {
            // Skip empty state row
            if (row.querySelector("td[colspan]")) {
                row.style.display = visibleCount === 0 ? "" : "none";
                return;
            }

            // Get row data
            const kodepompa =
                row
                    .querySelector("td:nth-child(3)")
                    ?.textContent.trim()
                    .toLowerCase() || "";
            const lokasi =
                row
                    .querySelector("td:nth-child(4)")
                    ?.textContent.trim()
                    .toLowerCase() || "";
            const injector =
                row
                    .querySelector("td:nth-child(5)")
                    ?.textContent.trim()
                    .toLowerCase() || "";
            const user =
                row
                    .querySelector("td:nth-child(6)")
                    ?.textContent.trim()
                    .toLowerCase() || "";
            const lokasiId = row.getAttribute("data-lokasi");
            const rowDate = row.getAttribute("data-date");
            const rowStatus = row.getAttribute("data-status");

            // Match filters
            const matchSearch =
                !searchValue ||
                kodepompa.includes(searchValue) ||
                lokasi.includes(searchValue) ||
                injector.includes(searchValue) ||
                user.includes(searchValue);

            const matchLokasi = !lokasiValue || lokasiId === lokasiValue;
            const matchStatus = !statusValue || rowStatus === statusValue;

            // Date range filter
            let matchDate = true;
            if (dateFrom && dateTo) {
                matchDate = rowDate >= dateFrom && rowDate <= dateTo;
            } else if (dateFrom) {
                matchDate = rowDate >= dateFrom;
            } else if (dateTo) {
                matchDate = rowDate <= dateTo;
            }

            // Show/hide row
            if (matchSearch && matchLokasi && matchStatus && matchDate) {
                row.style.display = "";
                visibleCount++;
            } else {
                row.style.display = "none";
            }
        });
    }

    // ===========================
    // 2. MODAL DETAIL REPORT
    // ===========================

    const detailModal = document.getElementById("detailReportModal");

    if (detailModal) {
        detailModal.addEventListener("show.bs.modal", function (event) {
            const button = event.relatedTarget;
            const reportId = button.getAttribute("data-id");

            // Show loading state
            showLoadingState();

            // Fetch report detail via AJAX
            fetch(`/admin/reports/${reportId}`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then((data) => {
                    populateModalData(data);
                })
                .catch((error) => {
                    console.error("Error fetching report detail:", error);
                    showErrorState();
                });
        });
    }

    function showLoadingState() {
        // Header info
        document.getElementById("detail_tanggal").innerHTML =
            '<i class="mdi mdi-loading mdi-spin"></i>';
        document.getElementById("detail_kodepompa").innerHTML =
            '<i class="mdi mdi-loading mdi-spin"></i>';
        document.getElementById("detail_jenispompa").innerHTML =
            '<i class="mdi mdi-loading mdi-spin"></i>';
        document.getElementById("detail_injector_well").innerHTML =
            '<i class="mdi mdi-loading mdi-spin"></i>';
        document.getElementById("detail_lokasi").innerHTML =
            '<i class="mdi mdi-loading mdi-spin"></i>';
        document.getElementById("detail_kodesp").innerHTML =
            '<i class="mdi mdi-loading mdi-spin"></i>';
        document.getElementById("detail_user").innerHTML =
            '<i class="mdi mdi-loading mdi-spin"></i>';
        document.getElementById("detail_status").innerHTML =
            '<i class="mdi mdi-loading mdi-spin"></i>';
    }

    function showErrorState() {
        const errorHTML =
            '<span class="text-danger"><i class="mdi mdi-alert-circle"></i> Error loading data</span>';
        document.getElementById("detail_hourly_data").innerHTML = `
            <tr><td colspan="11" class="text-center text-danger py-4">${errorHTML}</td></tr>
        `;
    }

    function populateModalData(data) {
        // Header Information
        document.getElementById("detail_tanggal").textContent = data.tanggal;
        document.getElementById("detail_kodepompa").textContent =
            data.kodepompa;
        document.getElementById("detail_jenispompa").textContent =
            data.jenispompa;
        document.getElementById("detail_injector_well").textContent =
            data.injeksi_ke;
        document.getElementById("detail_lokasi").textContent = data.lokasi;
        document.getElementById("detail_kodesp").textContent = data.kodesp;
        document.getElementById("detail_user").textContent = data.user;

        // Status badge
        const statusBadge = document.getElementById("detail_status");
        statusBadge.textContent = data.status_label;
        statusBadge.className = "badge";
        if (data.status === "approved") {
            statusBadge.classList.add("badge-approved");
        } else if (data.status === "rejected") {
            statusBadge.classList.add("badge-rejected");
        } else {
            statusBadge.classList.add("badge-pending");
        }

        // Hourly Data Table
        const hourlyDataBody = document.getElementById("detail_hourly_data");
        if (data.hourly_data && data.hourly_data.length > 0) {
            hourlyDataBody.innerHTML = data.hourly_data
                .map(
                    (item) => `
                <tr>
                    <td class="text-center"><strong>${item.jam_ke}</strong></td>
                    <td class="text-center">${item.total_bbls}</td>
                    <td class="text-center">${item.rate_jam}</td>
                    <td class="text-center">${item.cumm_bbls}</td>
                    <td class="text-center">${item.rate_hari}</td>
                    <td class="text-center">${item.inj_psi}</td>
                    <td class="text-center">${item.inj_rpm}</td>
                    <td class="text-center">${item.oil_cf}</td>
                    <td class="text-center">${item.press_cf}</td>
                    <td class="text-center">${item.water_cf}</td>
                    <td class="text-center">${item.freq_hz}</td>
                </tr>
            `
                )
                .join("");
        } else {
            hourlyDataBody.innerHTML = `
                <tr><td colspan="11" class="text-center text-muted py-4">Tidak ada data per jam</td></tr>
            `;
        }

        // Keterangan Table
        const keteranganBody = document.getElementById("detail_keterangan");
        if (data.keterangan && data.keterangan.length > 0) {
            keteranganBody.innerHTML = data.keterangan
                .map(
                    (ket) => `
                <tr>
                    <td>${ket.dari_jam || "-"}</td>
                    <td>${ket.sd_jam || "-"}</td>
                    <td>${ket.keterangan || "-"}</td>
                    <td>${ket.dt_jam || "-"}</td>
                </tr>
            `
                )
                .join("");
        } else {
            keteranganBody.innerHTML = `
                <tr><td colspan="4" class="text-center text-muted py-3">Tidak ada keterangan</td></tr>
            `;
        }

        // Summary
        document.getElementById("detail_total_cumulative").textContent =
            data.total_cumulative;
        document.getElementById("detail_running_hours").textContent =
            data.running_hours_text || "-";
        document.getElementById("detail_entries").textContent =
            data.entries_count;
        document.getElementById("detail_created_at").textContent =
            data.created_at;
        document.getElementById("detail_updated_at").textContent =
            data.updated_at;
    }

    // ===========================
    // 3. APPROVE REPORT
    // ===========================

    window.confirmApprove = function (reportId) {
        Swal.fire({
            title: "Approve Laporan?",
            html: `
                <p>Anda akan meng-approve laporan ini.</p>
                <p class="text-muted mb-0">Laporan yang sudah di-approve tidak dapat diubah.</p>
            `,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#6c757d",
            confirmButtonText:
                '<i class="mdi mdi-check-circle me-1"></i> Ya, Approve!',
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                submitApprove(reportId);
            }
        });
    };

    function submitApprove(reportId) {
        const form = document.createElement("form");
        form.action = `/admin/reports/${reportId}/approve`;
        form.method = "POST";
        form.style.display = "none";

        const csrfInput = document.createElement("input");
        csrfInput.setAttribute("type", "hidden");
        csrfInput.setAttribute("name", "_token");
        csrfInput.setAttribute(
            "value",
            document.querySelector('meta[name="csrf-token"]').content
        );
        form.appendChild(csrfInput);

        const methodInput = document.createElement("input");
        methodInput.setAttribute("type", "hidden");
        methodInput.setAttribute("name", "_method");
        methodInput.setAttribute("value", "PATCH");
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }

    // ===========================
    // 4. REJECT REPORT
    // ===========================

    window.confirmReject = function (reportId) {
        Swal.fire({
            title: "Reject Laporan?",
            html: `
                <p>Anda akan me-reject laporan ini.</p>
                <textarea id="reject_reason" class="form-control mt-2" 
                          placeholder="Alasan reject (opsional)" 
                          rows="3"></textarea>
            `,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#ffc107",
            cancelButtonColor: "#6c757d",
            confirmButtonText:
                '<i class="mdi mdi-close-circle me-1"></i> Ya, Reject!',
            cancelButtonText: "Batal",
            preConfirm: () => {
                return {
                    reason: document.getElementById("reject_reason").value,
                };
            },
        }).then((result) => {
            if (result.isConfirmed) {
                submitReject(reportId, result.value.reason);
            }
        });
    };

    function submitReject(reportId, reason) {
        const form = document.createElement("form");
        form.action = `/admin/reports/${reportId}/reject`;
        form.method = "POST";
        form.style.display = "none";

        const csrfInput = document.createElement("input");
        csrfInput.setAttribute("type", "hidden");
        csrfInput.setAttribute("name", "_token");
        csrfInput.setAttribute(
            "value",
            document.querySelector('meta[name="csrf-token"]').content
        );
        form.appendChild(csrfInput);

        const methodInput = document.createElement("input");
        methodInput.setAttribute("type", "hidden");
        methodInput.setAttribute("name", "_method");
        methodInput.setAttribute("value", "PATCH");
        form.appendChild(methodInput);

        if (reason) {
            const reasonInput = document.createElement("input");
            reasonInput.setAttribute("type", "hidden");
            reasonInput.setAttribute("name", "reject_reason");
            reasonInput.setAttribute("value", reason);
            form.appendChild(reasonInput);
        }

        document.body.appendChild(form);
        form.submit();
    }

    // ===========================
    // 5. DELETE REPORT
    // ===========================

    window.confirmDelete = function (reportId) {
        Swal.fire({
            title: "Hapus Laporan?",
            text: "Data laporan dan detail per jam akan dihapus permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: '<i class="mdi mdi-delete me-1"></i> Ya, Hapus!',
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                submitDelete(reportId);
            }
        });
    };

    function submitDelete(reportId) {
        const form = document.createElement("form");
        form.action = `/admin/reports/${reportId}`;
        form.method = "POST";
        form.style.display = "none";

        const csrfInput = document.createElement("input");
        csrfInput.setAttribute("type", "hidden");
        csrfInput.setAttribute("name", "_token");
        csrfInput.setAttribute(
            "value",
            document.querySelector('meta[name="csrf-token"]').content
        );
        form.appendChild(csrfInput);

        const methodInput = document.createElement("input");
        methodInput.setAttribute("type", "hidden");
        methodInput.setAttribute("name", "_method");
        methodInput.setAttribute("value", "DELETE");
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }
});
