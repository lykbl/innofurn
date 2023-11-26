'use server';
"use strict";
var __makeTemplateObject = (this && this.__makeTemplateObject) || function (cooked, raw) {
    if (Object.defineProperty) { Object.defineProperty(cooked, "raw", { value: raw }); } else { cooked.raw = raw; }
    return cooked;
};
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (g && (g = 0, op[0] && (_ = 0)), _) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.deleteInvoice = exports.updateInvoice = exports.createInvoice = void 0;
var zod_1 = require("zod");
var cache_1 = require("next/cache");
var navigation_1 = require("next/navigation");
var postgres_1 = require("@vercel/postgres");
var FormSchema = zod_1.z.object({
    id: zod_1.z.string(),
    customerId: zod_1.z.string(),
    amount: zod_1.z.coerce.number(),
    status: zod_1.z.enum(['pending', 'paid']),
    date: zod_1.z.string(),
});
var CreateInvoiceValidator = FormSchema.omit({ id: true, date: true });
function createInvoice(formData) {
    return __awaiter(this, void 0, void 0, function () {
        var _a, customerId, amount, status, amountInCents, date, error_1;
        return __generator(this, function (_b) {
            switch (_b.label) {
                case 0:
                    _a = CreateInvoice.parse({
                        customerId: formData.get('customerId'),
                        amount: formData.get('amount'),
                        status: formData.get('status'),
                    }), customerId = _a.customerId, amount = _a.amount, status = _a.status;
                    amountInCents = amount * 100;
                    date = new Date().toISOString().split('T')[0];
                    _b.label = 1;
                case 1:
                    _b.trys.push([1, 3, , 4]);
                    return [4 /*yield*/, (0, postgres_1.sql)(templateObject_1 || (templateObject_1 = __makeTemplateObject(["\n      INSERT INTO invoices (customer_id, amount, status, date)\n      VALUES (", ", ", ", ", ", ", ")\n    "], ["\n      INSERT INTO invoices (customer_id, amount, status, date)\n      VALUES (", ", ", ", ", ", ", ")\n    "])), customerId, amountInCents, status, date)];
                case 2:
                    _b.sent();
                    return [3 /*break*/, 4];
                case 3:
                    error_1 = _b.sent();
                    return [2 /*return*/, {
                            message: 'Database Error: Failed to Create Invoice.',
                        }];
                case 4:
                    (0, cache_1.revalidatePath)('/dashboard/invoices');
                    (0, navigation_1.redirect)('/dashboard/invoices');
                    return [2 /*return*/];
            }
        });
    });
}
exports.createInvoice = createInvoice;
var UpdateInvoice = FormSchema.omit({ id: true, date: true });
function updateInvoice(id, formData) {
    return __awaiter(this, void 0, void 0, function () {
        var _a, customerId, amount, status, amountInCents, error_2;
        return __generator(this, function (_b) {
            switch (_b.label) {
                case 0:
                    _a = UpdateInvoice.parse({
                        customerId: formData.get('customerId'),
                        amount: formData.get('amount'),
                        status: formData.get('status'),
                    }), customerId = _a.customerId, amount = _a.amount, status = _a.status;
                    amountInCents = amount * 100;
                    _b.label = 1;
                case 1:
                    _b.trys.push([1, 3, , 4]);
                    return [4 /*yield*/, (0, postgres_1.sql)(templateObject_2 || (templateObject_2 = __makeTemplateObject(["\n        UPDATE invoices\n        SET customer_id = ", ", amount = ", ", status = ", "\n        WHERE id = ", "\n      "], ["\n        UPDATE invoices\n        SET customer_id = ", ", amount = ", ", status = ", "\n        WHERE id = ", "\n      "])), customerId, amountInCents, status, id)];
                case 2:
                    _b.sent();
                    return [3 /*break*/, 4];
                case 3:
                    error_2 = _b.sent();
                    return [2 /*return*/, { message: 'Database Error: Failed to Update Invoice.' }];
                case 4:
                    (0, cache_1.revalidatePath)('/dashboard/invoices');
                    (0, navigation_1.redirect)('/dashboard/invoices');
                    return [2 /*return*/];
            }
        });
    });
}
exports.updateInvoice = updateInvoice;
function deleteInvoice(id) {
    return __awaiter(this, void 0, void 0, function () {
        var error_3;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0: throw new Error('Failed to Delete Invoice');
                case 1:
                    _a.trys.push([1, 3, , 4]);
                    return [4 /*yield*/, (0, postgres_1.sql)(templateObject_3 || (templateObject_3 = __makeTemplateObject(["DELETE FROM invoices WHERE id = ", ""], ["DELETE FROM invoices WHERE id = ", ""])), id)];
                case 2:
                    _a.sent();
                    (0, cache_1.revalidatePath)('/dashboard/invoices');
                    return [2 /*return*/, { message: 'Deleted Invoice.' }];
                case 3:
                    error_3 = _a.sent();
                    return [2 /*return*/, { message: 'Database Error: Failed to Delete Invoice.' }];
                case 4: return [2 /*return*/];
            }
        });
    });
}
exports.deleteInvoice = deleteInvoice;
var templateObject_1, templateObject_2, templateObject_3;
