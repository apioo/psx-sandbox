<?php
/*
 * PSX is an open source PHP framework to develop RESTful APIs.
 * For the current version and information visit <https://phpsx.org>
 *
 * Copyright 2010-2023 Christoph Kappestein <christoph.kappestein@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace PSX\Sandbox;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\PrettyPrinter\Standard;

/**
 * Printer
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class Printer5 extends Standard
{
    private SecurityManager $securityManager;

    public function __construct(SecurityManager $securityManager, array $options = [])
    {
        parent::__construct($options);

        $this->securityManager = $securityManager;
    }

    protected function pExpr_FuncCall(Expr\FuncCall $node): string
    {
        $functionName = $this->pCallLhs($node->name);

        $this->securityManager->checkFunctionCall($functionName, $node->args);

        return parent::pExpr_FuncCall($node);
    }

    protected function pExpr_Eval(Expr\Eval_ $node): string
    {
        throw new SecurityException('Eval is not allowed');
    }

    protected function pExpr_Include(Expr\Include_ $node, int $precedence, int $lhsPrecedence): string
    {
        throw new SecurityException('Include is not allowed');
    }

    protected function pExpr_ShellExec(Expr\ShellExec $node): string
    {
        throw new SecurityException('Shell exec is not allowed');
    }

    protected function pExpr_New(Expr\New_ $node): string
    {
        if ($node->class instanceof Stmt\Class_) {
            throw new SecurityException('Anonymous class is not allowed');
        }

        $class = $this->p($node->class);

        $this->securityManager->checkClassIsAllowed($class);

        return parent::pExpr_New($node);
    }

    protected function pStaticDereferenceLhs(Node $node): string
    {
        $class = $this->p($node);

        $this->securityManager->checkClassIsAllowed($class);

        return parent::pStaticDereferenceLhs($node);
    }

    protected function pExpr_Exit(Expr\Exit_ $node): string
    {
        throw new SecurityException('Exit is not allowed');
    }

    protected function pStmt_Interface(Stmt\Interface_ $node): string
    {
        throw new SecurityException('Interface is not allowed');
    }

    protected function pStmt_Class(Stmt\Class_ $node): string
    {
        throw new SecurityException('Class is not allowed');
    }

    protected function pStmt_Trait(Stmt\Trait_ $node): string
    {
        throw new SecurityException('Trait is not allowed');
    }

    protected function pStmt_TraitUse(Stmt\TraitUse $node): string
    {
        throw new SecurityException('Trait use is not allowed');
    }

    protected function pStmt_TraitUseAdaptation_Precedence(Stmt\TraitUseAdaptation\Precedence $node): string
    {
        throw new SecurityException('Trait use adaption is not allowed');
    }

    protected function pStmt_TraitUseAdaptation_Alias(Stmt\TraitUseAdaptation\Alias $node): string
    {
        throw new SecurityException('Trait use adaption alias is not allowed');
    }

    protected function pStmt_Property(Stmt\Property $node): string
    {
        throw new SecurityException('Property is not allowed');
    }

    protected function pStmt_PropertyProperty(Stmt\PropertyProperty $node): string
    {
        throw new SecurityException('Property property is not allowed');
    }

    protected function pStmt_ClassMethod(Stmt\ClassMethod $node): string
    {
        throw new SecurityException('Class method is not allowed');
    }

    protected function pStmt_ClassConst(Stmt\ClassConst $node): string
    {
        throw new SecurityException('Class const is not allowed');
    }

    protected function pStmt_Function(Stmt\Function_ $node): string
    {
        $this->securityManager->defineFunction((string)$node->name);

        return parent::pStmt_Function($node);
    }

    protected function pConst(\PhpParser\Node\Const_ $node): string
    {
        $this->securityManager->checkDefineConstant();
        return parent::pConst($node);
    }

    protected function pStmt_Declare(Stmt\Declare_ $node): string
    {
        throw new SecurityException('Declare is not allowed');
    }

    protected function pStmt_DeclareDeclare(Stmt\DeclareDeclare $node): string
    {
        throw new SecurityException('Declare declare is not allowed');
    }

    protected function pStmt_Echo(Stmt\Echo_ $node): string
    {
        throw new SecurityException('Echo is not allowed');
    }

    protected function pStmt_Expression(Stmt\Expression $node): string
    {
        $expression = $this->p($node->expr);

        if (\preg_match('/print\W+/i', $expression)) {
            throw new SecurityException('Print is not allowed');
        }

        return $expression . ';';
    }

    protected function pStmt_Global(Stmt\Global_ $node): string
    {
        throw new SecurityException('Global is not allowed');
    }

    protected function pStmt_InlineHTML(Stmt\InlineHTML $node): string
    {
        throw new SecurityException('Inline HTML is not allowed');
    }

    protected function pStmt_HaltCompiler(Stmt\HaltCompiler $node): string
    {
        throw new SecurityException('Halt compiler is not allowed');
    }

    protected function pClassCommon(Stmt\Class_ $node, $afterClassToken): string
    {
        throw new SecurityException('Class is not allowed');
    }

    protected function pStmt_Namespace(Stmt\Namespace_ $node): string
    {
        $this->securityManager->setCurrentNamespace($node->name !== null ? (string)$node->name : null);

        return parent::pStmt_Namespace( $node );
    }

    protected function pStmt_Use(Stmt\Use_ $node): string
    {
        foreach ($node->uses as $use) {
            if ($node->type === Stmt\Use_::TYPE_NORMAL) {
                $this->securityManager->addClassAlias((string)$use->name, $use->alias !== null ? (string)$use->alias : null);
            }
            elseif ($node->type === Stmt\Use_::TYPE_FUNCTION) {
                $this->securityManager->addFunctionAlias((string)$use->name, (string)($use->alias ?? $use->name));
            }
        }

        return parent::pStmt_Use($node);
    }

    protected function pStmt_GroupUse(Stmt\GroupUse $node): string
    {
        foreach ($node->uses as $use) {
            if ($node->type === Stmt\Use_::TYPE_NORMAL) {
                $this->securityManager->addClassAlias((string)$use->name, $use->alias !== null ? (string)$use->alias : null);
            }
            elseif ($node->type === Stmt\Use_::TYPE_FUNCTION) {
                $this->securityManager->addFunctionAlias((string)$use->name, (string)($use->alias ?? $use->name));
            }
        }

        return parent::pStmt_GroupUse($node);
    }

}
