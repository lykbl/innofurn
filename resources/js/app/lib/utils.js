"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.generatePagination = exports.generateYAxis = exports.formatDateToLocal = exports.formatCurrency = void 0;
var formatCurrency = function (amount) {
    return (amount / 100).toLocaleString('en-US', {
        style: 'currency',
        currency: 'USD',
    });
};
exports.formatCurrency = formatCurrency;
var formatDateToLocal = function (dateStr, locale) {
    if (locale === void 0) { locale = 'en-US'; }
    var date = new Date(dateStr);
    var options = {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    };
    var formatter = new Intl.DateTimeFormat(locale, options);
    return formatter.format(date);
};
exports.formatDateToLocal = formatDateToLocal;
var generateYAxis = function (revenue) {
    // Calculate what labels we need to display on the y-axis
    // based on highest record and in 1000s
    var yAxisLabels = [];
    var highestRecord = Math.max.apply(Math, revenue.map(function (month) { return month.revenue; }));
    var topLabel = Math.ceil(highestRecord / 1000) * 1000;
    for (var i = topLabel; i >= 0; i -= 1000) {
        yAxisLabels.push("$".concat(i / 1000, "K"));
    }
    return { yAxisLabels: yAxisLabels, topLabel: topLabel };
};
exports.generateYAxis = generateYAxis;
var generatePagination = function (currentPage, totalPages) {
    // If the total number of pages is 7 or less,
    // display all pages without any ellipsis.
    if (totalPages <= 7) {
        return Array.from({ length: totalPages }, function (_, i) { return i + 1; });
    }
    // If the current page is among the first 3 pages,
    // show the first 3, an ellipsis, and the last 2 pages.
    if (currentPage <= 3) {
        return [1, 2, 3, '...', totalPages - 1, totalPages];
    }
    // If the current page is among the last 3 pages,
    // show the first 2, an ellipsis, and the last 3 pages.
    if (currentPage >= totalPages - 2) {
        return [1, 2, '...', totalPages - 2, totalPages - 1, totalPages];
    }
    // If the current page is somewhere in the middle,
    // show the first page, an ellipsis, the current page and its neighbors,
    // another ellipsis, and the last page.
    return [
        1,
        '...',
        currentPage - 1,
        currentPage,
        currentPage + 1,
        '...',
        totalPages,
    ];
};
exports.generatePagination = generatePagination;
