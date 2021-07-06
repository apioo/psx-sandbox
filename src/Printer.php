<?php
/*
 * PSX is a open source PHP framework to develop RESTful APIs.
 * For the current version and informations visit <http://phpsx.org>
 *
 * Copyright 2010-2020 Christoph Kappestein <christoph.kappestein@gmail.com>
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

use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\PrettyPrinter\Standard;

/**
 * Printer
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class Printer extends Standard
{
    /**
     * @var \PSX\Sandbox\SecurityManager
     */
    protected $securityManager;

    /**
     * @param \PSX\Sandbox\SecurityManager $securityManager
     * @param array $options
     */
    public function __construct(SecurityManager $securityManager, array $options = [])
    {
        parent::__construct($options);

        $this->securityManager = $securityManager;
    }

    protected function pExpr_FuncCall(Expr\FuncCall $node)
    {
        $functionName = $this->pCallLhs($node->name);

        $this->securityManager->checkFunctionCall($functionName, $node->args);

        return parent::pExpr_FuncCall($node);
    }

    protected function pExpr_Eval(Expr\Eval_ $node)
    {
        throw new SecurityException('Eval is not allowed');
    }

    protected function pExpr_Include(Expr\Include_ $node)
    {
        throw new SecurityException('Include is not allowed');
    }

    protected function pExpr_ShellExec(Expr\ShellExec $node)
    {
        throw new SecurityException('Shell exec is not allowed');
    }

    protected function pExpr_New(Expr\New_ $node)
    {
        if ($node->class instanceof Stmt\Class_) {
            throw new SecurityException('Anonymous class is not allowed');
        }

        $class = $this->p($node->class);

        $this->securityManager->checkNewCall($class);

        return parent::pExpr_New($node);
    }

    protected function pExpr_Exit(Expr\Exit_ $node)
    {
        throw new SecurityException('Exit is not allowed');
    }

    protected function pStmt_Interface(Stmt\Interface_ $node)
    {
        throw new SecurityException('Interface is not allowed');
    }

    protected function pStmt_Class(Stmt\Class_ $node)
    {
        throw new SecurityException('Class is not allowed');
    }

    protected function pStmt_Trait(Stmt\Trait_ $node)
    {
        throw new SecurityException('Trait is not allowed');
    }

    protected function pStmt_TraitUse(Stmt\TraitUse $node)
    {
        throw new SecurityException('Trait use is not allowed');
    }

    protected function pStmt_TraitUseAdaptation_Precedence(Stmt\TraitUseAdaptation\Precedence $node)
    {
        throw new SecurityException('Trait use adaption is not allowed');
    }

    protected function pStmt_TraitUseAdaptation_Alias(Stmt\TraitUseAdaptation\Alias $node)
    {
        throw new SecurityException('Trait use adaption alias is not allowed');
    }

    protected function pStmt_Property(Stmt\Property $node)
    {
        throw new SecurityException('Property is not allowed');
    }

    protected function pStmt_PropertyProperty(Stmt\PropertyProperty $node)
    {
        throw new SecurityException('Property property is not allowed');
    }

    protected function pStmt_ClassMethod(Stmt\ClassMethod $node)
    {
        throw new SecurityException('Class method is not allowed');
    }

    protected function pStmt_ClassConst(Stmt\ClassConst $node)
    {
        throw new SecurityException('Class const is not allowed');
    }

    protected function pStmt_Function(Stmt\Function_ $node)
    {
        $this->securityManager->addAllowedFunction($node->name);

        return parent::pStmt_Function($node);
    }

    protected function pStmt_Declare(Stmt\Declare_ $node)
    {
        throw new SecurityException('Declare is not allowed');
    }

    protected function pStmt_DeclareDeclare(Stmt\DeclareDeclare $node)
    {
        throw new SecurityException('Declare declare is not allowed');
    }

    protected function pStmt_Echo(Stmt\Echo_ $node)
    {
        throw new SecurityException('Echo is not allowed');
    }

    protected function pStmt_Global(Stmt\Global_ $node)
    {
        throw new SecurityException('Global is not allowed');
    }

    protected function pStmt_InlineHTML(Stmt\InlineHTML $node)
    {
        throw new SecurityException('Inline HTML is not allowed');
    }

    protected function pStmt_HaltCompiler(Stmt\HaltCompiler $node)
    {
        throw new SecurityException('Halt compiler is not allowed');
    }

    protected function pClassCommon(Stmt\Class_ $node, $afterClassToken)
    {
        throw new SecurityException('Class is not allowed');
    }
}
