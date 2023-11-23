<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
        
    /**
     * Cria Usuario.
     *
     * @param  mixed $request
     * @param  mixed $usuarioRepository
     * @return JsonResponse
     */
    #[Route('/usuario/criar', name: 'usuario_criar')]
    public function createUsuario(Request $request, UsuarioRepository $usuarioRepository): JsonResponse
    {
        $data = $request->request->all();

        $usuario = new Usuario();
        
        $usuario->setEmail($data['email']);
        $usuario->setPassword($data['senha']);
        $usuario->setRoles([$data['role']]);

        $usuarioRepository->add($usuario);
        

        return $this->json([
            'message' => 'Usuario criado com sucesso',
            'data' => $usuario->getEmail()
        ]);
    }
    
    /**
     * Deleta Usuario.
     *
     * @param  mixed $id
     * @param  mixed $usuarioRepository
     * @return JsonResponse
     */
    #[Route('/usuario/deletar', name: 'usuario_delete')]
    public function deleteUsuario(int $id, UsuarioRepository $usuarioRepository): JsonResponse
    {
        $usuario = $usuarioRepository->find($id);
        $usuario = $usuarioRepository->remove($usuario, true);

        return $this->json([
            'message' => 'Usuario deletado com sucesso'
        ]);
    }
}
