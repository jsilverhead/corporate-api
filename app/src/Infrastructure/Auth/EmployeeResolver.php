<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth;

use App\Domain\Common\Exception\AccessIsDeniedException;
use App\Domain\Employee\Employee;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Auth\Exception\AuthorizationHeaderMissingException;
use RuntimeException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class EmployeeResolver implements ValueResolverInterface
{
    public function __construct(private Security $security)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (Employee::class !== $argument->getType()) {
            return [];
        }

        $user = $this->security->getUser();

        if (null === $user) {
            throw new AuthorizationHeaderMissingException();
        }

        if (!$user instanceof Employee) {
            throw new AccessIsDeniedException();
        }

        $allowedRoles = [];

        foreach ($argument->getAttributesOfType(AllowedUserRole::class) as $role) {
            $allowedRoles = array_merge($allowedRoles, $role->roles);
        }

        if (0 === \count($allowedRoles)) {
            throw new RuntimeException('AllowedUserRole attribute is required when using UserResolver');
        }

        if (false === \in_array($user->role, $allowedRoles, true)) {
            throw new AccessIsDeniedException();
        }

        yield $user;
    }
}
