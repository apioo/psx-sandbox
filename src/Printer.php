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
class Printer extends Standard
{
    private SecurityManager $securityManager;

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
        $this->securityManager->defineFunction((string) $node->name );

        return parent::pStmt_Function($node);
    }

    protected function pConst(\PhpParser\Node\Const_ $node)
    {
        $this->securityManager->checkDefineConstant();
        return parent::pConst($node);
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

    protected function pStmt_Namespace(Stmt\Namespace_ $node)
    {
        $this->securityManager->setCurrentNamespace($node->name !== null ? (string)$node->name : null);

        if ($this->canUseSemicolonNamespaces) {
            return 'namespace ' . $this->p($node->name) . ';'
                . $this->nl . $this->pStmts($node->stmts, false);
        } else {
            return 'namespace' . (null !== $node->name ? ' ' . $this->p($node->name) : '')
                . ' {' . $this->pStmts($node->stmts) . $this->nl . '}';
        }
    }

    protected function pStmt_Use(Stmt\Use_ $node)
    {
        foreach ($node->uses as $use) {
            if ($node->type === Stmt\Use_::TYPE_NORMAL) {
                $this->securityManager->addClassAlias((string)$use->name, (string)($use->alias ?? $use->name));
            }
            elseif ($node->type === Stmt\Use_::TYPE_FUNCTION) {
                $this->securityManager->addFunctionAlias((string)$use->name, (string)($use->alias ?? $use->name));
            }
        }

        return 'use ' . $this->pUseType($node->type)
            . $this->pCommaSeparated($node->uses) . ';';
    }

    protected function pStmt_GroupUse(Stmt\GroupUse $node)
    {
        foreach ($node->uses as $use) {
            if ($node->type === Stmt\Use_::TYPE_NORMAL) {
                $this->securityManager->addClassAlias((string)$use->name, (string)($use->alias ?? $use->name));
            }
            elseif ($node->type === Stmt\Use_::TYPE_FUNCTION) {
                $this->securityManager->addFunctionAlias((string)$use->name, (string)($use->alias ?? $use->name));
            }
        }

        return 'use ' . $this->pUseType($node->type) . $this->pName($node->prefix)
            . '\{' . $this->pCommaSeparated($node->uses) . '};';
    }

}
