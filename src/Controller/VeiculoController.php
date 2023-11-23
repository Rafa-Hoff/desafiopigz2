<?php

namespace App\Controller;

use App\Entity\Veiculo;
use App\Repository\VeiculoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VeiculoController extends AbstractController
{
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
    public function deleteVeiculos(int $id, VeiculoRepository $veiculosRepository): JsonResponse
    {
        $veiculo = $veiculosRepository->find($id);
        $veiculo = $veiculosRepository->remove($veiculo, true);

        return $this->json([
            'message' => 'Veiculo deletado com sucesso',
            'data' => $veiculo
        ]);
    }
}
