<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiaristaRequest;
use App\Models\Diarista;
use App\Services\ViaCEP;
use Illuminate\Http\Request;

class DiaristaController extends Controller
{
  public function __construct(
    protected ViaCEP $viaCep
  ) {
  }

  public function index() // Listando Diaristas
    {
      $diaristas = Diarista::get();
      return view('index', [
          'diaristas' => $diaristas
      ]);
    }

    public function create() // Mostra o formulário de criação
    {
      return view('create');
    }

    public function store(DiaristaRequest $request) // Cria uma diarista no banco de dados
    {
      $dados = $request->except('_token');
      $dados['foto_usuario'] = $request->foto_usuario->store('public');

      $dados['cpf'] = str_replace(['.', '-'], '', $dados['cpf']);
      $dados['cep'] = str_replace( '-', '', $dados['cep']);
      $dados['telefone'] = str_replace( ['(', ')', ' ', '-'], '', $dados['telefone']);
      $dados['codigo_ibge'] = $this->viaCep->buscar($dados['cep'])['ibge'];

      Diarista::create($dados);

      return redirect()->route('diaristas.index');
    }

    public function edit(int $id) // Mostra o formulário de edição
    {
      $diarista = Diarista::findOrFail($id);

      return view('edit', [
        'diarista' => $diarista
      ]);
    }

    public function update(int $id, DiaristaRequest $request)
    {
      $diarista = Diarista::findOrFail($id);

      $dados = $request->except(['_token', '_method']);

      $dados['cpf'] = str_replace(['.', '-'], '', $dados['cpf']);
      $dados['cep'] = str_replace( '-', '', $dados['cep']);
      $dados['telefone'] = str_replace( ['(', ')', ' ', '-'], '', $dados['telefone']);
      $dados['codigo_ibge'] = $this->viaCep->buscar($dados['cep'])['ibge'];

      if($request ->hasFile('foto_usuario')) {
        $dados['foto_usuario'] = $request->foto_usuario->store('public');
      }

      $diarista->update($dados);
      return redirect()->route('diaristas.index');
    }

    public function destroy(int $id)
    {
      $diarista = Diarista::findOrFail($id);

      $diarista->delete();

      return redirect()->route('diaristas.index');
    }
}
