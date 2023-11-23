<?php

namespace App\Controller;

use App\Entity\Fipe;
use App\Repository\FipeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FipeController extends AbstractController
{
    /**
     * Retorna todos os veiculos do banco de dados.
     *
     * @param  mixed $veiculosRepository
     * @return JsonResponse
     */
    #[Route('/fipe', name: 'fipe_veiculos_lista', methods: ['GET'])]    
    public function allVeiculosFipe(FipeRepository $fipeRepository): JsonResponse
    {
        $veiculos = $fipeRepository->findAll();
        
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
    #[Route('/fipe/{id}', name: 'fipe_veiculos_by_id', methods: ['GET'])]
    public function veiculoByIdFipe(int $id, FipeRepository $fipeRepository): JsonResponse
    {
        $veiculos = $fipeRepository->find($id);

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
    #[Route('/fipe/inserir', name: 'fipe_veiculos_inserir', methods: ['POST'])]
    public function createVeiculosFipe(Request $request, FipeRepository $fipeRepository): JsonResponse
    {
        $data = $request->request->all();
        
        $veiculo = new Fipe();
        $veiculo->setFabricante($data['fabricante']);
        $veiculo->setModelo($data['modelo']);
        $veiculo->setAno($data['ano']);
        $veiculo->setPreco($data['preco']);

        $fipeRepository->add($veiculo, true);

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
    #[Route('/fipe/update/{id}', name: 'fipe_veiculos_update', methods: ['PUT', 'PATCH'])]
    public function updateVeiculosFipe(int $id, Request $request, ManagerRegistry $doctrine, FipeRepository $fipeRepository): JsonResponse
    {
        $data = $request->request->all();
        $veiculo = $fipeRepository->find($id);

        $veiculo->setFabricante($data['fabricante']);
        $veiculo->setModelo($data['modelo']);
        $veiculo->setAno($data['ano']);
        $veiculo->setPreco($data['preco']);

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
    #[Route('/fipe/deletar/{id}', name: 'fipe_veiculos_deletar', methods: ['DELETE'])]
    public function deleteVeiculosFipe(int $id, FipeRepository $fipeRepository): JsonResponse
    {
        $veiculo = $fipeRepository->find($id);
        $veiculo = $fipeRepository->remove($veiculo, true);

        return $this->json([
            'message' => 'Veiculo deletado com sucesso',
            'data' => $veiculo
        ]);
    }
}
