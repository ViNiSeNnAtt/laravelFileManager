<?php

namespace App\Http\Controllers;

use App\Models\FilesModel;
use App\Models\FoldersModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Mockery\Exception;

class FilesController extends Controller
{

    public function newFile(Request $request)
    {
        try {
            if (!is_null($request->file('file'))){
                $caminho = $this->gerarCaminho($request->fileLocal);
                //Pega os dados do arquivo
                $file = $request->file('file');
                $filename = $file->getClientOriginalName();
                $filetype = $file->getClientMimeType();
                $filepath = $caminho . '/' . $filename;

                //Salva no storage
                Storage::disk(env('FILESYSTEM_DISK'))->put($filepath, file_get_contents($file));


                //Salva o caminho e os dados no banco
                $file = new FilesModel();
                $file->name = $filename;
                $file->type = $filetype;
                $file->path = $filepath;
                $file->parent_folder_id = $request->fileLocal;
                $file->save();
                return response()->json(['success'=>true, 'message'=>'Arquivo enviado']);
            } else {
                return response()->json(['success'=>false, 'message'=>'Não foi possivel encontrar seus arquivos']);
            }
        }
        catch (\Exception $exception){
            return response()->json(['success'=>false, 'message' => 'Erro:' . $exception->getMessage()]);
        }
    }
    public function favoriteFile(Request $request)
    {
        try {
            $file = FilesModel::where('id','=',$request->id)->first();
            if ($file->favorite == true){
                $file->favorite = false;
            } else {
                $file->favorite = true;
            }
            $file->save();
        }   catch (\Exception $exception){
            return response()->json(['success'=>false, 'message'=>'Erro:' . $exception->getMessage()]);
        }
    }
    public function downloadFile(Request $request)
    {
        try {
            $file = FilesModel::where('id', $request->id)->first();

            header('Content-type:' . $file->type);

            return Storage::download($file->path, $file->name);

        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => 'Erro: ' . $exception->getMessage()]);
        }
    }
    public function deleteFile(Request $request){}
    public function renameFile(Request $request){}
}
