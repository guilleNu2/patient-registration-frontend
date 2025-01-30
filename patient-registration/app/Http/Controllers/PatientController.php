<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{

//registrar pacientes
 public function store(Request $request)
 {
     $validatedData = $request->validate([
         'name' => 'required|string',
         'email' => 'required|email|unique:patients',
         'phone' => 'required|string',
         'document_photo' => 'required|string'
     ]);
      $patient = Patient::create($validatedData);
     
     return response()->json(["message"=>"the patient was succesfully created"], 201);
 }

    // Obtener todos los pacientes
    public function index()
    {
          return response()->json(Patient::all(), 200);
    }

   //️ Obtener un paciente por ID
        public function show($id)
        {
            $patient = Patient::find($id);
            if (!$patient) {
                return response()->json(['message' => 'Patient not found'], 404);
            }
            return response()->json($patient, 200);
        }
        
  public function update(Request $request, $id)
      {
          $patient = Patient::find($id);
          if (!$patient) {
              return response()->json(['message' => 'Patient not found'], 404);
          }
  
          $validator = Validator::make($request->all(), [
              'name' => 'string|max:255',
              'email' => 'email|unique:patients,email,' . $id,
              'phone' => 'string',
              'document_photo' => 'required|string',
          ]);
  
          if ($validator->fails()) {
              return response()->json(['errors' => $validator->errors()], 400);
          }
  
          if ($request->hasFile('document_photo')) {
              // Eliminar la foto anterior
              Storage::disk('public')->delete($patient->document_photo);
              // Guardar la nueva foto
              $path = $request->file('document_photo')->store('documents', 'public');
              $patient->document_photo = $path;
          }
  
          $patient->update($request->only(['name', 'email', 'phone']));
  
          return response()->json($patient, 200);
      }


    /**
     * Remove the specified resource from storage.
     */
   public function destroy($id)
       {
           $patient = Patient::find($id);
           if (!$patient) {
               return response()->json(['message' => 'Patient not found'], 404);
           }
   
           // Eliminar la foto del documento
           Storage::disk('public')->delete($patient->document_photo);
           $patient->delete();
   
           return response()->json(['message' => 'Patient deleted'], 200);
       }
}
