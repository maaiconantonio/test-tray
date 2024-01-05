<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
	public function index()
	{
		$sales = Sale::all();
		return response()->json($sales);
	}

	public function show($id)
	{
		$sale = Sale::find($id);
		return response()->json($sale);
	}

	public function showByVendor(Request $request, $id)
	{
		$vendor = Vendor::find($id);

		if (empty($vendor)) {
			return response()->json([
				"status" => "error",
				"msg" => utf8_encode("Vendedor não encontrado")
			]);
		}

		$sale = Sale::join("vendor", "vendor.id", "=", "sale.vendor_id")
			->where("vendor_id", $vendor->id)
			->select([
				"sale.id",
				"sale.vendor_id",
				"vendor.name",
				"vendor.mail",
				"sale.commission_value",
				"sale.sale_value",
				DB::raw("DATE_FORMAT(sale.created_at, '%d/%m/%Y') AS created_at")
			])->get();
		return response()->json($sale);
	}

	public function create(Request $request)
	{
		$sale = new Sale();
		if (empty($request->vendor_id) || empty($request->sale_value)) {
			return response()->json([
				"status" => "error",
				"msg" => utf8_encode("O vendedor e o valor da venda são campos obrigatórios")
			]);
		}
		
		$vendor = Vendor::find($request->vendor_id);
		
		if (empty($vendor)) {
			return response()->json([
				"status" => "error",
				"msg" => utf8_encode("Vendedor não encontrado")
			]);
		}

		$vendor_commission = $vendor->value("commission");
		$commission_value = $request->sale_value * $vendor_commission / 100;

		$sale->vendor_id = $request->vendor_id;
		$sale->sale_value = $request->sale_value;
		$sale->commission_value	= $commission_value;

		$sale->save();

		return response()->json([
			"id" => $sale->id,
			"vendor_id" => $sale->vendor_id,
			"nome" => $vendor->name,
			"mail" => $vendor->mail,
			"commission" => $sale->commission_value,
			"sale_value" => $sale->sale_value,
			"sale_date" => $sale->created_at->format("d/m/Y")
		]);
	}

	public function update(Request $request, $id)
	{
		$sale = Sale::find($id);

		$sale->vendor_id = $request->vendor_id;
		$sale->sale_value = $request->sale_value;

		$sale->save();
		return response()->json($sale);
	}

	public function delete($id)
	{
		$sale = Sale::find($id);
		$sale->delete();
		return response()->json([
			"status" => "success",
			"msg" => utf8_encode("Venda excluída com sucesso")
		]);
	}
}