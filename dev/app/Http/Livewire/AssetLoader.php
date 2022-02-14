<?php

namespace App\Http\Livewire;

use App\Models\Asset;
use App\Models\PasoReceta;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\File\File;

class AssetLoader extends Component
{
    use WithFileUploads;

    public $origen;
    public $id_modelo;
    public $modo;

    public $modelo;

    public $imagen;

    // protected $listeners = [
    //     'uploadImagen' => 'uploadImagen',
    // ];

    protected function getListeners()
    {
        return [
            'uploadImagen:' . $this->id_modelo => 'uploadImagen',
            'refresh:' . $this->id_modelo => '$refresh',
            'deleteAsset:' . $this->id_modelo => 'deleteAsset',
        ];
    }

    // TODO: Detectar si el origen al que pertence el paso es publico o no y obtener prefijo para el path(hacer en el controlador)

    public function mount($origen, $id_modelo, $modo)
    {
        $this->origen = $origen;
        $this->id_modelo = $id_modelo;
        
        if($modo == "edit"){
            $this->modo = "edit";
        }
        else{
            $this->modo = "show";
        }

        switch ($origen) {
            case 'PasoReceta':
                $this->modelo = PasoReceta::find($this->id_modelo);
                break;
            
            default:
                $this->modelo = null;
                break;
        }
    }

    public function render()
    {
        $assets = collect();
        $publico = "";

        if($this->modelo != null){          
            $publico = $this->modelo->esPublico();
            
            if($publico){
                $ruta = "/storage/";
            }
            else{
                $ruta = "users/" . $this->modelo->user()->id . "/";
            }

            $assets = $this->modelo->assets()->pluck('ruta');

            $rutas = collect();

            foreach ($assets as $r) {
                $rutas->push($ruta . $r);
            }
        }

        return view('livewire.asset-loader',[
            'modelo' => $this->modelo, 
            'rutas' => $rutas, 
            'publico' => $publico, 
            'assets' => $this->modelo->assets()->get() 
        ]);
    }


    public function uploadImagen($imagen)
    {
        if($this->modo == "edit"){
            $this->imagen = $imagen;

            // decode the base64 file
            $decoded = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagen));
            
            // save it to temporary dir first.
            $tmpFilePath = sys_get_temp_dir() . '/' . Str::uuid()->toString();
            file_put_contents($tmpFilePath, $decoded);

            // this just to help us get file info.
            $tmpFile = new File($tmpFilePath);

            $file = new UploadedFile(
                $tmpFile->getPathname(),
                $tmpFile->getFilename(),
                $tmpFile->getMimeType(),
                0,
                true // Mark it as test, since the file isn't from real HTTP POST.
            );

            if($this->modelo->esPublico()){
                $archivo = Storage::disk('public')->put('pasos',$file);
                //  $file->store('public/pasos');
            }
            else{
                $archivo = $file->store('users/' . $this->modelo->user()->id . '/pasos');
            }

            $asset = new Asset([
                'tipo' => 'local',
                'ruta' => $archivo,
                'remoto' => false,
            ]);

            $this->modelo->assets()->save($asset);

            $this->mount($this->origen, $this->id_modelo, $this->modo);
            $this->emit('refresh:'. $this->id_modelo);
            $this->render();

            // dd($archivo);
        }

        $this->render();
    }

    function deleteAsset($idAsset){
        // dd($idAsset);

        $asset = Asset::find($idAsset);

        if($asset){
            $asset->borradoCompleto();
        }

        $this->mount($this->origen, $this->id_modelo, $this->modo);
        $this->emit('refresh:'. $this->id_modelo);
        $this->render();
    }
}
