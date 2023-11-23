<?php

namespace App\Controller;

use App\Entity\Veiculo;
use App\Repository\FipeRepository;
use App\Repository\VeiculoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class VeiculoController extends AbstractController
{

        /**
     * Retorna os veiculos disponiveis para venda.
     *
     * @param  mixed $veiculoRepository
     * @return JsonResponse
     */
    #[Route('/veiculos/disponiveis', name: 'veiculos_lista_dispo', methods: ['GET'])]
    public function veiculosDisponiveis(VeiculoRepository $veiculoRepository): JsonResponse
    {
        //Veiculos disponiveis então atribuidos a variavel disponivel da tabela, (disponivel = true), (indisponivel = false).
        $veiculos = $veiculoRepository->findBy(['disponivel' => true]);

        return $this->json([
            'message' => 'Veiculos disponiveis para venda:',
            'data' => $veiculos
        ]);
    }

    /**
     * Retorna todos os veiculos do banco de dados.
     *
     * @param  mixed $veiculosRepository
     * @return JsonResponse
     */
    #[Route('/veiculos', name: 'veiculos_lista', methods: ['GET'])]    
    public function allVeiculos(VeiculoRepository $veiculosRepository): JsonResponse
    {
        $veiculos = $veiculosRepository->findAll();
        
        return $this->json([
            'data' => $veiculos
        ]);
    }

    /**
     * Retornar do banco de dados um veiculo pelo ID.
     *
     * @param  mixed $id
     * @param  mixed $veiculosRepository
     * @return JsonResponse
     */
    #[Route('/veiculos/{id}', name: 'veiculos_by_id', methods: ['GET'])]
    public function veiculoById(int $id, VeiculoRepository $veiculosRepository): JsonResponse
    {
        $veiculos = $veiculosRepository->find($id);

        return $this->json([
            'data' => $veiculos
        ]);
    }

    /**
     * Insere um novo veiculo no banco de dados
     *
     * @param  mixed $request
     * @param  mixed $veiculosRepository
     * @return JsonResponse
     */
    #[Route('/veiculos/inserir', name: 'veiculos_inserir', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function createVeiculos(Request $request, VeiculoRepository $veiculosRepository): JsonResponse
    {
        $data = $request->request->all();
        
        $veiculo = new Veiculo();
        $veiculo->setFabricante($data['fabricante']);
        $veiculo->setModelo($data['modelo']);
        $veiculo->setAno($data['ano']);
        $veiculo->setPreco($data['preco']);
        $veiculo->setDisponivel($data['disponivel']);

        $veiculosRepository->add($veiculo, true);

        return $this->json([
            'message' => 'Veiculo inserido com sucesso.',
            'data' => $veiculo
        ]);
    }
    
    /**
     * Atualiza os dados do veiculo no banco de dados.
     *
     * @param  mixed $id
     * @param  mixed $request
     * @param  mixed $doctrine
     * @param  mixed $veiculosRepository
     * @return JsonResponse
     */
    #[Route('/veiculos/update/{id}', name: 'veiculos_update', methods: ['PUT', 'PATCH'])]
    #[IsGranted('ROLE_ADMIN')]
    public function updateVeiculos(int $id, Request $request, ManagerRegistry $doctrine, VeiculoRepository $veiculosRepository): JsonResponse
    {
        $data = $request->request->all();
        $veiculo = $veiculosRepository->find($id);

        $veiculo->setFabricante($data['fabricante']);
        $veiculo->setModelo($data['modelo']);
        $veiculo->setAno($data['ano']);
        $veiculo->setPreco($data['preco']);
        $veiculo->setDisponivel($data['disponivel']);

        $doctrine->getManager()->flush();

        return $this->json([
            'message' => 'Veiculo atualizado com sucesso.',
            'data' => $veiculo
        ]);
    }
    
    /**
     * Deleta um veiculo especificado pelo ID.
     *
     * @param  mixed $id
     * @param  mixed $veiculosRepository
     * @return JsonResponse
     */
    #[Route('/veiculos/deletar/{id}', name: 'veiculos_deletar', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteVeiculos(int $id, VeiculoRepository $veiculosRepository): JsonResponse
    {
        $veiculo = $veiculosRepository->find($id);
        $veiculo = $veiculosRepository->remove($veiculo, true);

        return $this->json([
            'message' => 'Veiculo deletado com sucesso',
            'data' => $veiculo
        ]);
    }
    
    /**
     * Comparador de preços - irá receber o fabricante, modelo
     * e ano como parâmetros para comparar o valor com a Fipe.
     *
     * @param  mixed $fabricante
     * @param  mixed $modelo
     * @param  mixed $ano
     * @param  mixed $veiculosRepository
     * @param  mixed $fipeRepository
     * @return JsonResponse
     */
    #[Route('/veiculos/{fabricante}/{modelo}/{ano}', name: 'veiculos_comparados', methods: ['GET'])]
    public function comparador(string $fabricante, string $modelo, int $ano, VeiculoRepository $veiculosRepository, FipeRepository $fipeRepository): JsonResponse
    {
        //filtra na tabela Veiculos utilizando os dados informados, e armazena o veiculo encontrado na variável $veiculo.
        $veiculo = $veiculosRepository->findOneBy(['fabricante' => $fabricante,'modelo' => $modelo, 'ano' => $ano]);
        //filtra na tabela Fipe utilizando os dados informados, e armazena o preço do veiculo encontrado na variável $fipe.
        $fipe = $fipeRepository->findOneBy(['fabricante' => $fabricante,'modelo' => $modelo, 'ano' => $ano])->getPreco();

        $precoVeiculo = $veiculosRepository->findOneBy(['fabricante' => $fabricante,'modelo' => $modelo, 'ano' => $ano])->getPreco();
        $calculo = ($precoVeiculo - $fipe);
        if ($calculo < 0) {
            $calculo *= -1;
        }

        //retorna o veiculo encontrado com o preço original, o preço da tabela Fipe e a diferenca.
        return $this->json([
            'message' => 'Comparador de preco.',
            'data' => $veiculo,
            'Preco Tabela FIPE' => $fipe,
            'Diferenca de preco' => $calculo
        ]);
    }
}
