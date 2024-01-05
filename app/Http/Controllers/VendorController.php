<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
	public function index()
	{
		$vendors = Vendor::all();
		return response()->json($vendors);
	}

	public function show($id)
	{
		$vendor = Vendor::find($id);
		return response()->json($vendor);
	}

	public function create(Request $request)
	{
		$vendor = new Vendor();

		if (empty($request->name) || empty($request->mail)) {
			return response()->json([
				"status" => "error",
				"msg" => utf8_encode("Os campos Nome e Email são obrigatórios")
			]);
		}

		$vendor->name = $request->name;
		$vendor->mail = $request->mail;

		if (!empty($request->commission)) {
			$vendor->commission = $request->commission;
		} else {
			$vendor->commission = 8.5;
		}

		$vendor->save();
		return response()->json([
			"status" => "success",
			"msg" => utf8_encode("Vendedor cadastrado com sucesso")
		]);
	}

	public function update(Request $request, $id)
	{
		$vendor = Vendor::find($id);

		$vendor->name = $request->name;
		$vendor->mail = $request->mail;
		$vendor->commission = $request->commission;

		$vendor->save();
		return response()->json($vendor);
	}

	public function delete($id)
	{
		$vendor = Vendor::find($id);
		$vendor->delete();
		return response()->json([
			"status" => "success",
			"msg" => utf8_encode("Vendedor excluído com sucesso")
		]);
	}
}