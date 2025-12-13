// File: public/assets/js/admin/report-approved.js
// Approved Reports Page JavaScript

document.addEventListener("DOMContentLoaded", function () {
    // Get CSRF Token from meta tag
    const csrfToken =
        document.querySelector('meta[name="csrf-token"]')?.content || "";

    if (!csrfToken) {
        console.error("CSRF token not found!");
    }

    // Current report ID for modal actions
    let currentReportId = null;

    // ===========================
    // 1. FILTER & SEARCH FUNCTIONS
    // ===========================

    const searchInput = document.getElementById("searchReport");
    const filterDateFrom = document.getElementById("filterDateFrom");
    const filterDateTo = document.getElementById("filterDateTo");
    const filterLokasi = document.getElementById("filterLokasi");
    const filterApprovedBy = document.getElementById("filterApprovedBy");
    const resetFilterBtn = document.getElementById("resetFilter");

    if (searchInput) searchInput.addEventListener("keyup", filterTable);
    if (filterDateFrom) filterDateFrom.addEventListener("change", filterTable);
    if (filterDateTo) filterDateTo.addEventListener("change", filterTable);
    if (filterLokasi) filterLokasi.addEventListener("change", filterTable);
    if (filterApprovedBy)
        filterApprovedBy.addEventListener("change", filterTable);

    if (resetFilterBtn) {
        resetFilterBtn.addEventListener("click", function () {
            if (searchInput) searchInput.value = "";
            if (filterDateFrom) filterDateFrom.value = "";
            if (filterDateTo) filterDateTo.value = "";
            if (filterLokasi) filterLokasi.value = "";
            if (filterApprovedBy) filterApprovedBy.value = "";
            filterTable();
        });
    }

    function filterTable() {
        const searchValue = searchInput?.value.toLowerCase() || "";
        const dateFrom = filterDateFrom?.value || "";
        const dateTo = filterDateTo?.value || "";
        const lokasiValue = filterLokasi?.value || "";
        const approverValue = filterApprovedBy?.value || "";
        const tableRows = document.querySelectorAll("tbody tr");

        let visibleCount = 0;

        tableRows.forEach((row) => {
            if (row.querySelector("td[colspan]")) {
                row.style.display = visibleCount === 0 ? "" : "none";
                return;
            }

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
            const lokasiId = row.getAttribute("data-lokasi") || "";
            const rowDate = row.getAttribute("data-date") || "";
            const approverId = row.getAttribute("data-approver") || "";

            const matchSearch =
                !searchValue ||
                kodepompa.includes(searchValue) ||
                lokasi.includes(searchValue) ||
                injector.includes(searchValue) ||
                user.includes(searchValue);

            const matchLokasi = !lokasiValue || lokasiId === lokasiValue;
            const matchApprover =
                !approverValue || approverId === approverValue;

            let matchDate = true;
            if (dateFrom && dateTo) {
                matchDate = rowDate >= dateFrom && rowDate <= dateTo;
            } else if (dateFrom) {
                matchDate = rowDate >= dateFrom;
            } else if (dateTo) {
                matchDate = rowDate <= dateTo;
            }

            if (matchSearch && matchLokasi && matchApprover && matchDate) {
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

            if (!reportId) {
                console.error("Report ID not found");
                return;
            }

            currentReportId = reportId;
            showLoadingState();

            fetch(`/admin/reports/${reportId}/detail`)
                .then((response) => {
                    if (!response.ok)
                        throw new Error(
                            `HTTP error! status: ${response.status}`
                        );
                    return response.json();
                })
                .then((data) => populateModalData(data))
                .catch((error) => {
                    console.error("Error fetching report detail:", error);
                    showErrorState();
                });
        });
    }

    function showLoadingState() {
        const loadingHTML = '<i class="mdi mdi-loading mdi-spin"></i>';

        const headerElements = [
            "detail_tanggal",
            "detail_kodepompa",
            "detail_jenispompa",
            "detail_injector_well",
            "detail_lokasi",
            "detail_user",
            "detail_approved_by",
            "detail_approved_at",
        ];

        headerElements.forEach((id) => {
            const element = document.getElementById(id);
            if (element) element.innerHTML = loadingHTML;
        });

        const hourlyData = document.getElementById("detail_hourly_data");
        if (hourlyData) {
            hourlyData.innerHTML = `
                <tr><td colspan="11" class="text-center text-muted py-4">
                    ${loadingHTML} Memuat data...
                </td></tr>
            `;
        }

        const keteranganData = document.getElementById("detail_keterangan");
        if (keteranganData) {
            keteranganData.innerHTML = `
                <tr><td colspan="4" class="text-center text-muted py-3">
                    ${loadingHTML} Memuat data...
                </td></tr>
            `;
        }
    }

    function showErrorState() {
        const errorHTML =
            '<span class="text-danger"><i class="mdi mdi-alert-circle"></i> Error</span>';
        document.getElementById("detail_hourly_data").innerHTML = `
            <tr><td colspan="11" class="text-center text-danger py-4">${errorHTML}</td></tr>
        `;
    }

    function populateModalData(data) {
        const setText = (id, value) => {
            const element = document.getElementById(id);
            if (element) element.textContent = value || "-";
        };

        setText("detail_tanggal", data.tanggal);
        setText("detail_kodepompa", data.kodepompa);
        setText("detail_jenispompa", data.jenispompa);
        setText("detail_injector_well", data.injeksi_ke);
        setText("detail_lokasi", data.lokasi);
        setText("detail_user", data.user);
        setText("detail_approved_by", data.approved_by_name || "-");
        setText("detail_approved_at", data.approved_at || "-");

        const hourlyDataBody = document.getElementById("detail_hourly_data");
        if (hourlyDataBody) {
            if (data.hourly_data && data.hourly_data.length > 0) {
                hourlyDataBody.innerHTML = data.hourly_data
                    .map(
                        (item) => `
                    <tr>
                        <td class="text-center"><strong>${
                            item.jam_ke || "-"
                        }</strong></td>
                        <td class="text-center">${item.total_bbls || "-"}</td>
                        <td class="text-center">${item.rate_jam || "-"}</td>
                        <td class="text-center">${item.cumm_bbls || "-"}</td>
                        <td class="text-center">${item.rate_hari || "-"}</td>
                        <td class="text-center">${item.inj_psi || "-"}</td>
                        <td class="text-center">${item.inj_rpm || "-"}</td>
                        <td class="text-center">${item.oil_cf || "-"}</td>
                        <td class="text-center">${item.press_cf || "-"}</td>
                        <td class="text-center">${item.water_cf || "-"}</td>
                        <td class="text-center">${item.freq_hz || "-"}</td>
                    </tr>
                `
                    )
                    .join("");
            } else {
                hourlyDataBody.innerHTML = `
                    <tr><td colspan="11" class="text-center text-muted py-4">Tidak ada data</td></tr>
                `;
            }
        }

        const keteranganBody = document.getElementById("detail_keterangan");
        if (keteranganBody) {
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
        }

        setText("detail_total_cumulative", data.total_cumulative);
        setText("detail_entries", data.entries_count);
        setText("detail_created_at", data.created_at);
        setText("detail_updated_at", data.updated_at);
    }

    // ===========================
    // 3. PRINT REPORT
    // ===========================

    window.printReport = function (reportId) {
        window.open(`/admin/reports/${reportId}/print`, "_blank");
    };

    window.printCurrentReport = function () {
        if (currentReportId) {
            window.open(`/admin/reports/${currentReportId}/print`, "_blank");
        }
    };

    // ===========================
    // 4. DOWNLOAD EXCEL
    // ===========================

    window.downloadExcel = function (reportId) {
        window.location.href = `/admin/reports/${reportId}/export/excel`;
    };

    window.downloadCurrentReport = function () {
        if (currentReportId) {
            window.location.href = `/admin/reports/${currentReportId}/export/excel`;
        }
    };

    // ===========================
    // 5. REVOKE APPROVAL (Optional)
    // ===========================

    window.confirmRevoke = function (reportId) {
        if (!window.Swal) {
            alert("SweetAlert2 library not loaded!");
            return;
        }

        Swal.fire({
            title: "Batalkan Approval?",
            html: `
                <p>Laporan akan dikembalikan ke status <strong>Submitted</strong>.</p>
                <p class="text-danger mb-0">Tindakan ini dapat dibatalkan kembali.</p>
            `,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#ffc107",
            cancelButtonColor: "#6c757d",
            confirmButtonText:
                '<i class="mdi mdi-undo me-1"></i> Ya, Batalkan Approval!',
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                submitRevoke(reportId);
            }
        });
    };

    function submitRevoke(reportId) {
        const form = document.createElement("form");
        form.action = `/admin/reports/${reportId}/revoke-approval`;
        form.method = "POST";
        form.style.display = "none";

        const csrfInput = document.createElement("input");
        csrfInput.setAttribute("type", "hidden");
        csrfInput.setAttribute("name", "_token");
        csrfInput.setAttribute("value", csrfToken);
        form.appendChild(csrfInput);

        const methodInput = document.createElement("input");
        methodInput.setAttribute("type", "hidden");
        methodInput.setAttribute("name", "_method");
        methodInput.setAttribute("value", "PATCH");
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }

    console.log("Approved Reports JavaScript Loaded");
});
