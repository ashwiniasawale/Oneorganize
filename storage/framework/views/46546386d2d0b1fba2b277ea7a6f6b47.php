<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Appointment Letter')); ?>

<?php $__env->stopSection(); ?>
<?php use Carbon\Carbon; ?>
<?php $__env->startSection('content'); ?>
<div class="row" >

    

<div id="boxes" style="padding-right:30px;padding-left:30px;">

    
    <div id="header" style="padding:0px;margin-top:0px;padding-top:0px;display: flex; align-items: start; justify-content: space-between; border-bottom: 1px solid black; ">
    <img src="<?php echo e(asset('assets/images/logo/gms-logo.jpeg')); ?>" alt="gms-logo" style="width: 35%;">
    <div>
        <h1 style="margin: 0; font-size: 16px;font-weight:700;">GetMy Solutions Pvt. Ltd.</h1>
        <span style="font-size: 6px; line-height: 1.2;">
       <p style="margin: 0;"><strong>Head Office:</strong> 406/7, City Centre,</p>
       <p style="margin: 0;"> Hinjewadi Ph 1, Pune-411057. </p>
      <p style="margin: 0;">+91 80 870 87000</p>
       <p style="margin: 0;">contact@getmysolution.com</p>
       <p style="margin: 0;">www.gms.design</p>
     
    </span>
    </div>
</div>
<br>
    <div style="padding-right:10px;padding-left:10px;font-family: Arial, sans-serif; text-align: justify;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <p><strong>Ref. No. <?php echo e($employees->ref_no); ?></strong></p>
            <p><strong>Date: <?php echo e(Carbon::now()->format('F d, Y')); ?></p></strong>
        </div>


        <p><strong>To,</strong> <br>
        <?php echo e($employees->title); ?> <?php echo e($obj['employee_name']); ?> <br>
            Pune.</p>
        
<?php if($employees->title=='Mr.')
{
    $him_her='him';
    $his_her='his';
    $he_she='he';
}else{
    $him_her='her';
    $his_her='her';
    $he_she='she';
} ?>
        <p style="text-align: center; font-weight: bold; "><u>Subject: Letter of
                Appointment<u></p>

        <p style="font-weight: bold;">Dear  <?php echo e($obj['employee_name']); ?>,</p>

        <p>With reference to your acceptance of our offer letter dated <strong><?php echo e(date('jS F, Y', strtotime($employees->offer_date))); ?></strong>, the management is hereby pleased
            to appoint you in our organization <strong>GetMy Solutions Pvt. Ltd.</strong> w.e.f. <strong><?php echo e(date('jS F, Y', strtotime($employees->joining_date))); ?></strong> on the following terms &
            conditions:</p>

        <ol>
            <li>
               <strong><u>Designation:<u></strong>
                <p>You will be designated as <strong><?php echo e($obj['designation']); ?></strong> in the Product Development Department.</p>
            </li>
            <li>
                <strong><u>Place of Posting:</u></strong>
                <p>You will be posted in our office at <strong>GMS, 406/7, City Centre, Hinjewadi Phase 1, Pune-411057.</strong> However, at any
                    time during the period of appointment, you will be liable to transfer in such other capacity that
                    the company may determine to any other Department/ Branch/ Establishment or any other Company under
                    the same management without adversely affecting your emoluments and general condition of service.
                </p>

            </li>
            
            <li>
                <strong><u>Employment:</u></strong>
                <p>You will be on probation for the period of three months and subsequently Confirmed  from the date of 
                    joining for 1 year. After that, you will be part of a Project team. In the event of any violation by
                     employee of any of the terms of this Agreement, employer may terminate employment without notice and
                      with compensation to employee only to the date of such termination.</p>

            </li>
            <li >
                <strong><u>Remuneration:</u></strong>
                <p>You will be entitled to the remuneration as specified in the Annexure A.</p>
                
            </li>
            <li>
                <strong><u>Notice period:</u></strong>
                <p>During service period, either party, by stating their intention to do so, in writing may terminate this 
                    employment at any time, provided that at least 3 months’ notice. In case of non-performance of the candidate, 
                    company reserves rights to terminate the employee with immediate effect.</p>

            </li>
        </ol>



        <div id="header" style="padding:0px;margin-top:100px;padding-top:0px;display: flex; align-items: start; justify-content: space-between; border-bottom: 1px solid black; ">
            <img src="<?php echo e(asset('assets/images/logo/gms-logo.jpeg')); ?>" alt="gms-logo" style="width: 35%;">
            <div>
                <h1 style="margin: 0; font-size: 16px;font-weight:700;">GetMy Solutions Pvt. Ltd.</h1>
                <span style="font-size: 6px; line-height: 1.2;">
            <p style="margin: 0;"><strong>Head Office:</strong> 406/7, City Centre,</p>
            <p style="margin: 0;"> Hinjewadi Ph 1, Pune-411057. </p>
            <p style="margin: 0;">+91 80 870 87000</p>
            <p style="margin: 0;">contact@getmysolution.com</p>
            <p style="margin: 0;">www.gms.design</p>
            
            </span>
            </div>
        </div>
        <br>


        <strong><u>Other Rules & Organization:<u></strong>
        <p>In consideration of the covenants and agreements herein contained and the moneys to be paid hereunder, the Company 
            hereby employs the Employee and the Employee hereby agrees to perform services as an employee of the Company, on 
            an “at will” basis, upon the following terms and conditions:</p>

        <ol>
            <li>
                <p><strong>EMPLOYMENT:</strong></p>
                <ul style="list-style-type: upper-alpha;">
                <li> Company employs, engages, and hires employee and employee accepts and agrees to such hiring, engagement,
                     and employment, subject to the general supervision and pursuant to the orders, advice, and direction of employer.
                </li>
                <li>
                Employee shall perform such other duties as are customarily performed by one holding such position in other, 
                same, or similar businesses or enterprises as that engaged in by employer, and shall also additionally render 
                such other and unrelated services and duties as may be assigned to <?php echo e($him_her); ?> from time to time by employer.
                </li>
                </ul>
                    </li>
                    <br>
            <li>
                <p><strong>BEST EFFORTS OF EMPLOYEE:</strong></p>
                <p>Employee agrees that <?php echo e($he_she); ?> will at all times faithfully, industriously, and to the best of <?php echo e($his_her); ?> ability,
                     experience, and talents, perform all of the duties that may be required of and from <?php echo e($him_her); ?> pursuant to the
                      express and implicit terms of this Agreement, to the reasonable satisfaction of employer. Such duties shall be 
                      rendered at designated office and at such other place or places as employer shall in good faith require or
                       as the interest, needs, business, or opportunity of employer shall require.</p>

            </li>
            <li>
                <p><strong>TERMINATION DUE TO DISCONTINUANCE OF BUSINESS:</strong></p>
                <p>In spite of anything contained in this Agreement to the contrary, in the event that employer shall discontinue operating its business, 
                    then this Agreement shall terminate as of the last day of the month in which employer ceases operations at such location with the same
                     force and effect as if such last day of the month were originally set as the termination date of this Agreement.</p>
            </li>
            <li>
                <p><strong>OTHER EMPLOYMENT:</strong></p>
                <p>Employee shall devote all of <?php echo e($his_her); ?> time, attention, knowledge, and skills solely to the business and interest of employer, and employer 
                    shall be entitled to all of the benefits, profits, or other issues arising from or incident to all work, services, and advice of employee,
                    and employee shall not, during the term of this Agreement, be interested directly or indirectly, in any manner, as partner, officer, director,
                     shareholder, advisor, employee, or in any other capacity in any other company.</p>

            </li>
            <div id="header" style="padding:0px;margin-top:25px;padding-top:0px;display: flex; align-items: start; justify-content: space-between; border-bottom: 1px solid black; ">
            <img src="<?php echo e(asset('assets/images/logo/gms-logo.jpeg')); ?>" alt="gms-logo" style="width: 35%;">
            <div>
                <h1 style="margin: 0; font-size: 16px;font-weight:700;">GetMy Solutions Pvt. Ltd.</h1>
                <span style="font-size: 6px; line-height: 1.2;">
            <p style="margin: 0;"><strong>Head Office:</strong> 406/7, City Centre,</p>
            <p style="margin: 0;"> Hinjewadi Ph 1, Pune-411057. </p>
            <p style="margin: 0;">+91 80 870 87000</p>
            <p style="margin: 0;">contact@getmysolution.com</p>
            <p style="margin: 0;">www.gms.design</p>
            
            </span>
            </div>
        </div>
        <br>
            <li>
                <p><strong>REIMBURSEMENT OF EXPENSES:</strong></p>
                <p>The Employee may incur reasonable expenses for furthering the Company's business, including expenses for entertainment, travel, and similar items.
                     The Company shall reimburse Employee for all business expenses after the Employee presents an itemized account of expenditures, pursuant to Company policy.</p>

            </li>
            <li>
                <p><strong>RECOMMENDATIONS FOR IMPROVING OPERATIONS:</strong></p>
                <p>Employee shall make available to employer all information of which employee shall have any knowledge and shall make all suggestions and recommendations that
                     will be of mutual benefit to employer and employee.</p>

            </li>
            <li>
                <p><strong>EMPLOYEE’S INABILITY TO CONTRACT FOR EMPLOYER:</strong></p>
                <p>In spite of anything contained in this Agreement to the contrary, employee shall not have the right to make any contracts or commitments for or on behalf of 
                    employer without first obtaining the express written consent of employer.</p>

            </li>
            <li>
                <p><strong>COMPANY'S TRADE SECRETS:</strong></p>
                <p>Employee understands that in performance of <?php echo e($his_her); ?> job duties with the Company, Employee will be exposed to the Company's trade secrets. "Trade secrets"
                     means information or material that is commercially valuable to the Company and not generally known in the industry. This includes:</p>
                <ul style="list-style-type: upper-alpha;">
                    <li>Any and all versions of the Company's proprietary system (including source code and object
                        code), hardware, firmware, and documentation;</li>
                    <li>Technical information concerning the Company's products and services including product data and
                        specifications, diagrams, flow charts, drawings, test results, know-how, processes, inventions,
                        research projects, and product development;</li>
                    <li>Information concerning the Company's business including cost information, profits, sales
                        information, accounting and unpublished financial information, business plans, markets and
                        marketing methods, customer lists and customer information, purchasing techniques, supplier
                        lists and supplier information, and advertising strategies;</li>
                    <li>Information concerning the Company's employees including their salaries, strengths, weaknesses,
                        and skills;</li>
                    <li>Information submitted by the Company's customers, suppliers, employees, consultants, or
                        co-venturers with the Company for study, evaluation, or use; and</li>
                    <li>Any other information not generally known to the public which if misused or disclosed could
                        reasonably be expected to adversely affect the Company's business.</li>
                </ul>
            </li>
            <div id="header" style="padding:0px;margin-top:40px;padding-top:0px;display: flex; align-items: start; justify-content: space-between; border-bottom: 1px solid black; ">
            <img src="<?php echo e(asset('assets/images/logo/gms-logo.jpeg')); ?>" alt="gms-logo" style="width: 35%;">
            <div>
                <h1 style="margin: 0; font-size: 16px;font-weight:700;">GetMy Solutions Pvt. Ltd.</h1>
                <span style="font-size: 6px; line-height: 1.2;">
            <p style="margin: 0;"><strong>Head Office:</strong> 406/7, City Centre,</p>
            <p style="margin: 0;"> Hinjewadi Ph 1, Pune-411057. </p>
            <p style="margin: 0;">+91 80 870 87000</p>
            <p style="margin: 0;">contact@getmysolution.com</p>
            <p style="margin: 0;">www.gms.design</p>
            
            </span>
            </div>
        </div>
        <br>
            <li>
                <p><strong>NONDISCLOSURE OF TRADE SECRETS</strong></p>
                <p>The employee will keep the Company's trade secrets, whether or not prepared or developed by <?php echo e($him_her); ?>,
                    in the strictest confidence. The employee will not use or disclose such secrets to others without
                    the Company's written consent except when necessary to perform <?php echo e($his_her); ?> job. However, the employee
                    shall have no obligation to treat as confidential any information which:</p>
                <ul style="list-style-type: upper-alpha;">
                    <li>Was in <?php echo e($his_her); ?> possession or known to <?php echo e($him_her); ?> without an obligation to keep it confidential
                        before such information was disclosed to the employee by the Company;</li>
                    <li>Is or becomes public knowledge through a source other than the employee and through no fault of
                        the employee; or</li>
                    <li>Is or becomes lawfully available to the employee from a source other than the Company.</li>
                </ul>
            </li>
            <br>
            <li>
                <p><strong>CONFIDENTIAL INFORMATION OF OTHERS</strong></p>
                <p>Employee will not disclose to the Company, use in the Company's business, or cause the Company to use,
                     any information or material that is a trade secret of others. <?php echo e($his_her); ?> performance of this Agreement 
                     will not breach any agreement to keep in confidence proprietary information acquired by Employee prior to
                      <?php echo e($his_her); ?> employment by the Company.</p>

            </li>
            <li>
                <p><strong>RETURN OF MATERIALS</strong></p>
                <p>When <?php echo e($his_her); ?> employment with the Company ends, for whatever reason, Employee will promptly deliver to the Company
                     all originals and copies of all documents, records, software programs, media and other materials containing any of
                    the Company's trade secrets. Employee will also return to the Company all equipment, files, software programs and
                    other personal property belonging to the Company.</p>

            </li>
            <li>
                <p><strong>CONFIDENTIALITY OBLIGATION SURVIVES EMPLOYMENT </strong></p>
                <p>Employee understand that <?php echo e($his_her); ?> obligation to maintain the confidentiality and security of the Company's trade
                     secrets remains with Employee even after <?php echo e($his_her); ?> employment with the Company ends and continues for so long as
                      such material remains a trade secret.</p>
            </li>

            <li>
                <p><strong>COMPUTER PROGRAM ARE WORKS MADE FOR HIRE </strong></p>
                <p>Employee understand that as part of <?php echo e($his_her); ?> job duties Employee may be asked to create, or
                    contribute to the creation of, computer programs, documentation and other copyrightable works.
                    Employee agree that any and all computer programs, documentation and other copyrightable materials
                    that Employee is asked to prepare or work on as part of <?php echo e($his_her); ?> employment with the Company shall
                    be "works made for hire" and that the Company shall own all the copyright 
                </p>
            </li>
            <div id="header" style="padding:0px;margin-top:0px;padding-top:0px;display: flex; align-items: start; justify-content: space-between; border-bottom: 1px solid black; ">
            <img src="<?php echo e(asset('assets/images/logo/gms-logo.jpeg')); ?>" alt="gms-logo" style="width: 35%;">
            <div>
                <h1 style="margin: 0; font-size: 16px;font-weight:700;">GetMy Solutions Pvt. Ltd.</h1>
                <span style="font-size: 6px; line-height: 1.2;">
            <p style="margin: 0;"><strong>Head Office:</strong> 406/7, City Centre,</p>
            <p style="margin: 0;"> Hinjewadi Ph 1, Pune-411057. </p>
            <p style="margin: 0;">+91 80 870 87000</p>
            <p style="margin: 0;">contact@getmysolution.com</p>
            <p style="margin: 0;">www.gms.design</p>
            
            </span>
            </div>
        </div>
        <br>
        <p> rights in such works. If
        and to the extent any such material does not satisfy the legal requirements to constitute a work
        made for hire,employee hereby assign all <?php echo e($his_her); ?> copyright rights in the work to the company.</p>
            <li>
                <p><strong>DISCLOSURE OF DEVELOPMENTS</strong></p>
                <p>While Employee is employed by the Company, Employee will promptly inform the Company of the full
                    details of all <?php echo e($his_her); ?> inventions, discoveries, improvements, innovations and ideas (collectively
                    called "Developments") – whether or not patentable, copyrightable or otherwise protectible – that
                    Employee conceives, completes or reduces to practice (whether jointly or with others) and which:
                </p>
                <ul style="list-style-type: upper-alpha;">
                    <li>Relate to the Company's present or prospective business, or actual or demonstrably anticipated
                        research and development; or</li>
                    <li>Result from any work Employee do using any equipment, facilities, materials, trade secrets or
                        personnel of the Company; or</li>
                    <li>Result from or are suggested by any work that Employee may do for the Company.</li>
                </ul>
            </li>
<br>
            <li>
                <p><strong>ASSIGNMENT OF DEVELOPMENTS</strong></p>
                <p>Employee hereby assigns to the Company or the Company's designer, <?php echo e($his_her); ?> entire right, title and
                    interest in all of the following, that Employee conceives or makes (whether alone or with others)
                    while employed by the Company: </p>
                <ul style="list-style-type: upper-alpha;">
                    <li>All Developments;</li>
                    <li>All copyrights, trade secrets, trademarks and mask work rights in Developments; and</li>
                    <li>All patent applications filed and patents granted on any Developments, including those in
                        foreign countries.</li>
                </ul>
            </li>
            <br>
            <li>
                <p><strong> POST-EMPLOYMENT ASSIGNMENT</strong></p>
                <p>Employee will disclose to the Company any and all computer programs, inventions, improvements or
                    discoveries actually made, or copyright registration or patent applications filed, within 24 months
                    after <?php echo e($his_her); ?> employment with the Company ends. Employee hereby assigns to the Company <?php echo e($his_her); ?>

                    entire right, title and interest in such programs, inventions, improvements and discoveries, whether
                    made individually or jointly, which relate to the subject matter of <?php echo e($his_her); ?> employment with the
                    Company during the 24 month period immediately preceding the termination of <?php echo e($his_her); ?> employment.
                </p>
            </li>

            <li>
                <p><strong>NON COMPETITION </strong></p>
                <p>Employee agrees and covenants that because of the confidential and sensitive nature of the
                   
                </p>
                <div id="header" style="padding:0px;margin-top:0px;padding-top:0px;display: flex; align-items: start; justify-content: space-between; border-bottom: 1px solid black; ">
            <img src="<?php echo e(asset('assets/images/logo/gms-logo.jpeg')); ?>" alt="gms-logo" style="width: 35%;">
            <div>
                <h1 style="margin: 0; font-size: 16px;font-weight:700;">GetMy Solutions Pvt. Ltd.</h1>
                <span style="font-size: 6px; line-height: 1.2;">
            <p style="margin: 0;"><strong>Head Office:</strong> 406/7, City Centre,</p>
            <p style="margin: 0;"> Hinjewadi Ph 1, Pune-411057. </p>
            <p style="margin: 0;">+91 80 870 87000</p>
            <p style="margin: 0;">contact@getmysolution.com</p>
            <p style="margin: 0;">www.gms.design</p>
            
            </span>
            </div>
        </div>
        <br>
                    <p> Confidential Information and because the use of, or even the appearance of the use of, the
                    Confidential Information in certain circumstances may cause irreparable damage to Company and its
                    reputation, or to clients of Company, Employee shall not, until the expiration of two years after
                    the termination of the employment relationship between Company and Employee, engage, directly or
                    indirectly, or through any corporations or associates in any business, enterprise or employment
                    which is directly competitive with Company. </p>
            </li>

            <li>
                <p><strong>EXECUTION OF DOCUMENTS</strong></p>
                <p> Both while employed by the Company and afterwards, Employee agrees to execute and aid in the
                    preparation of any papers that the Company may consider necessary or helpful to obtain or maintain
                    any patents, copyrights, trademarks or other proprietary rights at no charge to the Company, but at
                    its expense. <br>
                    If the Company is unable to secure <?php echo e($his_her); ?> signature on any document necessary to obtain or
                    maintain any patent, copyright, trademark or other proprietary rights, whether due to <?php echo e($his_her); ?>

                    mental or physical capacity or any other cause, Employee hereby irrevocably designates and appoints
                    the Company and its duly authorized officers and agents as <?php echo e($his_her); ?> agents and attorneys-in-fact to
                    execute and file such documents and do all other lawfully permitted acts to further the prosecution,
                    issuance and enforcement of patents, copyrights and other proprietary rights with the same force and
                    effect as if executed by Employee.</p>
            </li>

            <li>
                <p><strong> CONFLICT OF INTEREST</strong></p>
                <p> During his/her employment by the Company, Employee will not engage in any business activity
                    competitive with the Company's business activities. Nor will Employee engage in any other activities
                    that conflict with the Company's best interests.</p>
            </li>

            <li>
                <p><strong>POST-EMPLOYMENT NON_COMPETITION AGGREMENT</strong></p>
                <p>Employee understand that during <?php echo e($his_her); ?> employment by the Company Employee may become Employee
                    familiar with confidential information of the Company. Therefore, it is possible that Employee could
                    gravely harm the Company if Employee worked for a competitor. Accordingly, Employee agrees for <b> 2
                        years</b> following the end of <?php echo e($his_her); ?> employment with the Company not to compete, directly or
                    indirectly, with the Company in any of its business if the duties of such competitive employment
                    inherently require that Employee use or disclose any of the Company's confidential information.
                    Competition includes the design, development, production, promotion or sale of products or services
                    competitive with those of the Company. Employee agrees not to engage in, or contribute his / her
                    knowledge to, any work that is competitive with or functionally similar to a product, process,
                    apparatus or service on which Employee worked while at the Company. The following post-employment
                    non-competition terms shall apply also: 
                </p>
            </li>
            <div id="header" style="padding:0px;margin-top:20px;padding-top:0px;display: flex; align-items: start; justify-content: space-between; border-bottom: 1px solid black; ">
            <img src="<?php echo e(asset('assets/images/logo/gms-logo.jpeg')); ?>" alt="gms-logo" style="width: 35%;">
            <div>
                <h1 style="margin: 0; font-size: 16px;font-weight:700;">GetMy Solutions Pvt. Ltd.</h1>
                <span style="font-size: 6px; line-height: 1.2;">
            <p style="margin: 0;"><strong>Head Office:</strong> 406/7, City Centre,</p>
            <p style="margin: 0;"> Hinjewadi Ph 1, Pune-411057. </p>
            <p style="margin: 0;">+91 80 870 87000</p>
            <p style="margin: 0;">contact@getmysolution.com</p>
            <p style="margin: 0;">www.gms.design</p>
            
            </span>
            </div>
        </div>
        <br>
            <p> <b>Diversion of Company Business: </b>For a period of
                    <b>2 years </b> from the date <?php echo e($his_her); ?> employment ends, Employee will not divert or attempt to
                    divert from the Company any business the Company enjoyed or solicited from its customers prior to
                    the termination of <?php echo e($his_her); ?> employment.</p>
            <li>
                <p><strong> NONINTERFERENCE WITH COMPANY EMPLOYEES </strong></p>
                <p>While employed by the Company, Employee will not: </p>
                <ul style="list-style-type: upper-alpha;">
                    <li>Induce, or attempt to induce, any Company employee to quit the Company's employ,</li>
                    <li>Recruit or hire away any Company employee, or</li>
                    <li>Hire or engage any Company employee or former employee whose employment with the Company ended
                        less than one year before the date of such hiring or engagement.</li>
                </ul>
            </li>
            <br>
            <li>
                <p><strong> ENFORCEMENT </strong></p>
                <p> Employee agree that in the event of a breach or threatened breach of this Agreement, money damages
                    would be an inadequate remedy and extremely difficult to measure. Employee agree, therefore, that
                    the Company shall be entitled to an injunction to restrain Employee from such breach or threatened
                    breach. Nothing in this Agreement shall be construed as preventing the Company from pursuing any
                    remedy at law or in equity for any breach or threatened breach.</p>
            </li>

            <li >
                <p><strong>SUCCESSORS </strong></p>
                <p> The rights and obligations under this Agreement shall survive the termination of <?php echo e($his_her); ?> service
                    to the Company in any capacity and shall inure to the benefit and shall be binding upon: (1) <?php echo e($his_her); ?> heirs and personal representatives, and (2) the successors and assigns of the Company.</p>
            </li>

            <li>
                <p><strong>GOVERNING LAW</strong></p>
                <p>This Agreement shall be construed and enforced in accordance with the laws of the Central and State
                    Govt. and amendments from time to time. Any other country laws are not applicable. You will be
                    governed by all applicable Social security laws- Provident Fund , ESI and Gratuity Act. And payment
                    will be done as per Act. </p>
            </li>

            <li>
                <p><strong> SEXUAL HARASSMENT POLICY</strong></p>
                <p>The GetMy Solutions Pvt. Ltd., is an equal employment opportunity company and is committed to
                    creating a healthy working environment that enables employees to work without fear of prejudice,
                    gender bias and sexual harassment. The Company also believes that all employees of the Company, have
                    the right to be treated with dignity. Sexual harassment at the work place or other than work place
                    if involving employees is a grave offence and is, therefore, punishable. Please refer to our Policy
                    document for more details. </p>
            </li>
            <div id="header" style="padding:0px;margin-top:0px;padding-top:0px;display: flex; align-items: start; justify-content: space-between; border-bottom: 1px solid black; ">
            <img src="<?php echo e(asset('assets/images/logo/gms-logo.jpeg')); ?>" alt="gms-logo" style="width: 35%;">
            <div>
                <h1 style="margin: 0; font-size: 16px;font-weight:700;">GetMy Solutions Pvt. Ltd.</h1>
                <span style="font-size: 6px; line-height: 1.2;">
            <p style="margin: 0;"><strong>Head Office:</strong> 406/7, City Centre,</p>
            <p style="margin: 0;"> Hinjewadi Ph 1, Pune-411057. </p>
            <p style="margin: 0;">+91 80 870 87000</p>
            <p style="margin: 0;">contact@getmysolution.com</p>
            <p style="margin: 0;">www.gms.design</p>
            
            </span>
            </div>
        </div>
        <br>
            <li>
                <p><strong>SEVERABILITY </strong></p>
                <p> If any provision of this Agreement is determined to be invalid or unenforceable, the remainder shall
                    be unaffected and shall be enforceable against both the Company and Employee.</p>
            </li>
           
            <li>
                <p><strong> ENTIRE AGGREEMENT </strong></p>
                <p> This Agreement supersedes and replaces all former agreements or understandings, oral or written,
                    between the Company and Employee, except for prior confidentiality agreements Employee has signed
                    relating to information not covered by this Agreement.</p>
            </li>

            <li>
                <p><strong>MODIFICATION </strong></p>
                <p>This Agreement may not be modified except by a writing signed both by the Company and Employee except
                    for the Other Rules & Organization section, where the Company may introduce corrections or additions
                    if any. </p>
            </li>

            <li style="margin-top:2em">
                <p><strong> RESPONSIBILITY</strong></p>
                <p> Employee is completely responsible for the Material provided to <?php echo e($him_her); ?> by Company. No personal
                    storage material e.g. USB Data drive are allowed to be used inside office. It is Employee
                    responsibility to take care of the Devices and Materials available inside and provided by the
                    Company. Any important document like ID Card/Letter lost by the employee, need to be informed to
                    HR immediately for safety purpose. Keep the office environment safe is Employee responsibility.
                    Employee shall take due to not cause harm to people or property knowingly or unknowingly.
                    Company is not responsible and liable for any kind of damages or legal actions for any problems
                    /issues,legal or non legal, where employee is directly or indirectly involved, and is not linked
                    to Company or not happened inside Company premises. <br>Company holds right to take necessary legal
                    or monitory action against employee if any intentional non-responsible or unethical behavior is
                    observed against Company or any individual.
                </p>
            </li>

            <li>
                <p><strong>ASSIGNMENT </strong></p>
                <p>This Agreement may be assigned by the Company. Employee may not assign or delegate <?php echo e($his_her); ?> duties
                    under this Agreement without the Company's prior written approval.
                </p>
            </li>

            <li>
                <p><strong> APPLICABILITY </strong></p>
                <p>All above rules and regulations can be changed as per government rules and regulations and company
                    policies time to time. </p>
            </li>

        </ol>
        <div id="header" style="padding:0px;margin-top:25px;padding-top:0px;display: flex; align-items: start; justify-content: space-between; border-bottom: 1px solid black; ">
            <img src="<?php echo e(asset('assets/images/logo/gms-logo.jpeg')); ?>" alt="gms-logo" style="width: 35%;">
            <div>
                <h1 style="margin: 0; font-size: 16px;font-weight:700;">GetMy Solutions Pvt. Ltd.</h1>
                <span style="font-size: 6px; line-height: 1.2;">
            <p style="margin: 0;"><strong>Head Office:</strong> 406/7, City Centre,</p>
            <p style="margin: 0;"> Hinjewadi Ph 1, Pune-411057. </p>
            <p style="margin: 0;">+91 80 870 87000</p>
            <p style="margin: 0;">contact@getmysolution.com</p>
            <p style="margin: 0;">www.gms.design</p>
            
            </span>
            </div>
        </div>
        <br>
        <p><strong>ACKNOWLEDGMENT</strong></p>
        <p>Employee has carefully read and considered all provisions of this Agreement and agrees that all of the
            restrictions set forth are fair and reasonably required to protect the Company's interests. Employee
            acknowledges that <?php echo e($he_she); ?> has received a copy of this Agreement as signed by <?php echo e($him_her); ?>.</p>

   <p>Yours faithfully,</p>
        <p><strong>FOR GETMY SOLUTIONS PVT. LTD.</strong></p>

        <div style="margin-top:17em; display: flex; align-items: start; justify-content: space-between; gap: 20px;">

            <div>
                <p>EMPLOYEE</p>
                <div style="margin-top: 2em;"> <span style="padding: 0; margin: 0; ">______________</span>
                    <p style="padding: 0; margin: 0; font-size: 14px;">Authorized Signature</p>
                </div>

                <div style="margin-top: 2em;">
                    <span style="padding: 0; margin: 0;">______________</span>
                    <p style="padding: 0; margin: 0; font-size: 14px;">Print Name and Title</p>
                </div>
            </div>
            <div>
                <p>COMPANY</p>
                <div style="margin-top: 2em;"> <span style="padding: 0; margin: 0; ">______________</span>
                    <p style="padding: 0; margin: 0; font-size: 14px;">Authorized Signature</p>
                </div>

                <div style="margin-top: 2em;">
                    <span style="padding: 0; margin: 0;">______________</span>
                    <p style="padding: 0; margin: 0; font-size: 14px;">Print Name and Title</p>
                </div>
            </div>

        </div>



        <div id="header" style="padding:0px;margin-top:270px;padding-top:0px;display: flex; align-items: start; justify-content: space-between; border-bottom: 1px solid black; ">
            <img src="<?php echo e(asset('assets/images/logo/gms-logo.jpeg')); ?>" alt="gms-logo" style="width: 35%;">
            <div>
                <h1 style="margin: 0; font-size: 16px;font-weight:700;">GetMy Solutions Pvt. Ltd.</h1>
                <span style="font-size: 6px; line-height: 1.2;">
            <p style="margin: 0;"><strong>Head Office:</strong> 406/7, City Centre,</p>
            <p style="margin: 0;"> Hinjewadi Ph 1, Pune-411057. </p>
            <p style="margin: 0;">+91 80 870 87000</p>
            <p style="margin: 0;">contact@getmysolution.com</p>
            <p style="margin: 0;">www.gms.design</p>
            
            </span>
            </div>
        </div>
        <br>
        <div >
            <p>Annexure A:</p>
            <h4 style="margin: 0 0 10px 0; text-align: center;">Salary Structure</h4>
            <table style="border: none;  margin-top: 20px; font-weight: bold;">
               <tbody>
                <tr style="line-height:1.5px;">
                    <td style="padding: 10px; text-align: left;">Name</td>
                    <td style="padding: 10px; text-align: left;">:</td>
                    <td style="padding: 10px; text-align: left;"><?php echo e($employees->title); ?> <?php echo e($obj['employee_name']); ?></td>
                </tr>
                <tr style="line-height:1.5px;">
                    <td style="padding: 10px; text-align: left;">Post</td>
                    <td style="padding: 10px; text-align: left;">:</td>
                    <td style="padding: 10px; text-align: left;"><?php echo e($obj['designation']); ?>  	</td>
                </tr>
                <tr style="line-height:1.5px;">
                    <td style="padding: 10px; text-align: left;">Joining Date</td>
                    <td style="padding: 10px; text-align: left;">:</td>
                    <td style="padding: 10px; text-align: left;"><?php echo e(date('jS F, Y', strtotime($employees->joining_date))); ?></td>
                </tr>
                <tr>
               </tbody>
            </table>
            <p style="margin-top: 10px;">All Figures in Indian Rupees</p>
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr>
                        <th
                            style="padding: 10px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">
                            Earnings</th>
                        <th
                            style="padding: 10px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">
                            Yearly</th>
                        <th
                            style="padding: 10px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">
                            Monthly</th>
                    </tr>
                </thead>
                <tbody>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">Basic</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e($structure['basicSal']); ?>.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e(round($structure['basicSal']/12)); ?>.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">HRA 30%</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e($structure['HRA']); ?>.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e(round($structure['HRA']/12)); ?>.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">Fixed Allow.</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e($structure['fixedAllow']); ?>.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e(round($structure['fixedAllow']/12)); ?>.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;"><strong>Total
                                        CTC</strong></td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">
                                    <strong>₹<?php echo e($employees->salary); ?>.00</strong>
                                </td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">
                                    <strong>₹<?php echo e(round($structure['totalCTC']/12)); ?>.00</strong>
                                </td>
                            </tr>
                        </tbody>
            </table>
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr>
                        <th
                            style="padding: 10px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">
                            Deductions</th>
                        <th
                            style="padding: 10px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">
                            Yearly</th>
                        <th
                            style="padding: 10px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">
                            Monthly</th>
                    </tr>
                </thead>
                <tbody>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">PF Employee
                                    contribution</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e($structure['PF1']); ?>.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e(round($structure['PF1']/12)); ?>.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">PF Employer
                                    contribution</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e($structure['PF2']); ?>.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e(round($structure['PF2']/12)); ?>.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">PT</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e($structure['PT']); ?>.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e(round($structure['PT'] / 12)); ?>.00
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">Insurance</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e($structure['insurance']); ?>.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e(round($structure['insurance']/12)); ?>.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">Gratuity</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e($structure['gratuity']); ?>.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e(round($structure['gratuity']/12)); ?>.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;"><strong>Total
                                        Deductions</strong></td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">
                                    <strong>₹<?php echo e(round($structure['totalDeduction'])); ?>.00</strong>
                                </td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">
                                    <strong>₹<?php echo e(round($structure['monthlyTotalDed'])); ?>.00</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;"><strong>Net In
                                        Hand</strong></td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">
                                    <strong>₹<?php echo e(round($structure['netInHand'])); ?>.00</strong>
                                </td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">
                                    <strong>₹<?php echo e(round($structure['monthlyInhand'])); ?>.00</strong>
                                </td>
                            </tr>
                        </tbody>
            </table>
<br>
            <p><strong>NOTE: </strong>Please do not discuss the salary with any employee.</p>
            <!-- <div id="footer"
            style=" margin-top: 4em; padding: 10px; text-align: start; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid black;">
            <span class="pageNumber" style="font-size: 12px;">
                +91 80 870 87000 <br>
                contact@getmysolution.com <br>
                <a href="http://www.gms.design/" style="color: black; text-decoration: none;">www.gms.design</a>
            </span>
        </div> -->
        </div>

    </div>
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
    <script type="text/javascript" src="<?php echo e(asset('js/html2pdf.bundle.min.js')); ?>"></script>
    <script>
        function closeScript() {
            setTimeout(function () {
                window.open(window.location, '_self').close();
            }, 1000);
        }

         $(window).on('load', function () {
            var element = document.getElementById('boxes');
        var format = 'letter';  // Default format

        // Check screen width to adjust PDF size
        if (window.innerWidth <= 480) {
            format = 'a5';  // Narrower format for mobile devices
        }
             var opt = {
                 margin: [0.5, 0.5, 0.5, 0.5], // top, right, bottom, left margins in inches
                 filename: 'GMS-Appointment Letter-<?php echo e($employees->employee_name); ?>.pdf',
                 image: {type: 'jpeg', quality: 1},
                 html2canvas: {scale: 1.5, dpi: 72, letterRendering: true},
                 jsPDF: {unit: 'in', format: 'letter', orientation: 'portrait'},
                 
             };

             html2pdf().set(opt).from(element).save().then(closeScript);
         });

        
    </script>
    
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.contractheader', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/employee/template/appointmentpdf.blade.php ENDPATH**/ ?>